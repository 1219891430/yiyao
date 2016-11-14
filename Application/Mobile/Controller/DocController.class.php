<?php

namespace Mobile\Controller;
use Think\Controller;


class DocController extends Controller{
    private $method = [];

    private $comm = ["name","method","uri","param","response"];

    public function __construct(){
        parent::__construct();
    }

    //获取所有需要解释成文档的控制器
    private function files(){
        $apis = C("handler");
        return $apis;
    }

    //获取控制器内所有方法和注释
    private function methods(){
        $handlers = $this->files();
        foreach($handlers as $val){

            $class = new \ReflectionClass($val);

            $methods = $class->getMethods(\ReflectionProperty::IS_PUBLIC);

            $method = [];
            foreach($methods as $m){
                if($m->class == $val && $m->name != "__construct"){
                    $comment = $m->getDocComment();
                    $classComment = $class->getDocComment();
                    $method["class"] = $m->class;
                    $method["comment"] = $classComment;
                    $method[] = [
                        "name"=>$m->name,
                        "comment"=>$comment
                    ];
                }
            }
            $this->method[] = $method;
        }
        return $this->method;
    }

    private function matchComment($str, $match = ["name"]){
        $match_arr = [];
        foreach($match as $m){
            if(preg_match_all('/@'.$m.'{1} (.*)/', $str, $matchstr)){
                $match_arr[$m] = str_replace(array("\r\n", "\r", "\n"), '', $matchstr[1]);
            }
        }

        return $match_arr;
    }

    private function matchParam($params) {

        foreach ($params as $k => $param) {
            $arr = explode(' ',$param);
            if (isset($arr[0])) {
                $parr[$k]['name'] = $arr[0];
            } else {
                $parr[$k]['name'] = '';
            }

            if (isset($arr[1])) {
                $parr[$k]['type'] = $arr[1];
            } else {
                $parr[$k]['type'] = '';
            }

            if (isset($arr[2])) {
                $parr[$k]['note'] = $arr[2];
            } else {
                $parr[$k]['note'] = '';
            }

        }

        return $parr;

    }

    private function comment(){
        $methods = $this->methods();
        $comment_doc = [];
        foreach($methods as $m){
            $doc = [];
            if(!empty($m["comment"])){
                $m_comment = $this->matchComment($m["comment"]);
            }
            if(isset($m_comment["name"])){
                $doc["class_name"] = $m_comment["name"];
            }

            $comment = [];
            foreach($m as $method){
                if(is_array($method)){
                    $comment = $this->matchComment($method["comment"],$this->comm);
                }

                if (!empty($comment["param"])) {
                    $comment["param"] = $this->matchParam($comment["param"]);
                }

                if (!empty($comment["response"])) {
                    $comment["response"] = $this->matchParam($comment["response"]);
                }

                if(!empty($comment)){
                    $doc[] = ["comment" => $comment];
                }

            }
            if(!empty($doc)){
                $comment_doc[] = $doc;
            }
        }

        return $comment_doc;
    }

    public function json(){
        $comment = $this->comment();
        $json = json_encode($comment);

        return $json;
    }

    public function viewAction(){
        $json_doc = $this->json();

        $array = json_decode($json_doc, true);

        $this->assign("json", $array);

        $this->display();
    }
    
    

}
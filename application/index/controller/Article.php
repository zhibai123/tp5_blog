<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
class Article extends Controller
{

    public function index()
    {

        $cateres=Db::name('cate')->order('id asc')->select();
        $this->assign('cateres',$cateres);
        //右侧热门点击和推荐阅读
        $clickres=db('article')->order('click desc')->limit(8)->select();
        $tjres=db('article')->where('state','=',1)->order('click desc')->limit(8)->select();
        $this->assign(array(
            'clickres'=>$clickres,
            'tjres'=>$tjres
        ));


        $arid=input('arid');
        $articles=db('article')->find($arid);
        $ralateres=$this->ralat($articles['keywords'],$articles['id']);
        //dump($ralateres); die;
        db('article')->where('id','=',$arid)->setInc('click');
        $cates=db('cate')->find($articles['cateid']);
        $recres=db('article')->where(array('cateid'=>$cates['id'],'state'=>1))->limit(8)->select();
        $this->assign(array(
            'articles'=>$articles,
            'cates'=>$cates,
            'recres'=>$recres,
            'ralateres'=>$ralateres
        ));
        return $this->fetch('article');

        
    }

    public function ralat($keywords,$id){
        $arr=explode(',', $keywords);
        static $ralateres=array();
        foreach ($arr as $k=>$v) {
            $map['keywords']=['like','%'.$v.'%'];
            $map['id']=['neq',$id];
            $artres=db('article')->where($map)->order('id desc')->limit(8)->select();
            $ralateres=array_merge($ralateres,$artres);
        }
        if($ralateres){

            $ralateres=arr_unique($ralateres);

            return $ralateres;

        }

    }


}

<?php
namespace app\admin\controller;

use think\Controller;
use app\admin\model\Cate as cateModel;
use think\Db;
use app\admin\controller\Base;
class cate extends Controller
{
    public function lst()
    {
        $list=cateModel::paginate(3);
        $this->assign('list',$list);
        return $this->fetch('lst');
    }
    public function add()
    {
        if(request()->isPost()){

            $data=[
                'username'=>input('username'),
                'password'=>input('password'),
            ];
            $validate = \think\Loader::validate('cate');
            if(!$validate->scene('add')->check($data)){
                $this->error($validate->getError()); die;
            }
            if(Db::name('cate')->insert($data)){
                return $this->success('添加栏目成功！','lst');
            }else{
                return $this->error('添加栏目失败！');
            }
            return;
        }
        return $this->fetch();
    }
    public function edit(){
        $id=input('id');
        $cates=db('cate')->find($id);
         //dump($cates); die;
        if(request()->isPost()){
            $data=[
                'id'=>input('id'),
                'username'=>input('username'),
            ];
            if(input('password')){
                $data['password']=/*md5*/(input('password'));
            }else{
                $data['password']=$cates['password'];
            }
            $validate = \think\Loader::validate('cate');
            if(!$validate->scene('edit')->check($data)){
                $this->error($validate->getError()); die;
            }
            $save=db('cate')->update($data);
            if($save !== false){
                $this->success('修改栏目成功！','lst');
            }else{
                $this->error('修改栏目失败！');
            }
            return;
        }
        $this->assign('cates',$cates);
        return $this->fetch();
    }

    public function del(){
        $id=input('id');
        if($id != 2){
            if(db('cate')->delete(input('id'))){
                $this->success('删除管理员成功！','lst');
            }else{
                $this->error('删除管理员失败！');
            }
        }else{
            $this->error('初始化管理员不能删除！');
        }

    }

    public function logout(){
        session(null);
        $this->success('退出成功！','Login/index');
    }
}

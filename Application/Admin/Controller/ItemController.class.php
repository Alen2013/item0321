<?php
/**
 * Created by PhpStorm.
 * User: 20258475
 * Date: 2018/3/16
 * Time: 8:38
 */
namespace Admin\Controller;

class ItemController extends BaseController {
    /**
     * 获取菜单列表
     * @author zhaoxiang <zhaoxiang051405@gmail.com>
     */
    public function software() {
        $list = D('ApiItem')->where('class="软件项目"')->select();
        //$list = formatTree(listToTree($list));
       // dump($list);
        $this->assign('list', $list);
        $this->display();
    }

    public function improve()
    {
        $list = D('ApiItem')->where('class="品质改善"')->select();
        //$list = formatTree(listToTree($list));
        // dump($list);
        $this->assign('list', $list);
        $this->display();
    }

    /**
     * 新增菜单
     * @author zhaoxiang <zhaoxiang051405@gmail.com>
     */
    public function add() {
       if (IS_POST) {
            $data = I('post.');
           // $data['status'] = isset($data['status']) ? 1 : 0;
            $res = D('ApiItem')->add($data);
            if ($res === false) {
                $this->ajaxError('操作失败');
            } else {
                $this->ajaxSuccess('添加成功');
            }
        } else {
            $originList = D('ApiAuthGroup')->order('id asc')->select();
           $member = D('ApiUser')->order('nickname asc')->select();
          //  $class = '';
            $id = I('get.id');
           // if (!empty($id)) {
           //     $group = $id;
           // }
           // $options = array_column(formatTree(listToTree($originList)), 'name', 'id');
            $this->assign('options', $originList);
           $this->assign('important', $member);
           // $this->assign('group', $class);
            $this->display();
        }
    }

    /**
     * 显示菜单
     * @author zhaoxiang <zhaoxiang051405@gmail.com>
     */
    public function open() {
        $id = I('post.id');
        $res = D('ApiMenu')->where(array('id' => $id))->save(array('hide' => 0));
        if ($res === false) {
            $this->ajaxError('操作失败');
        } else {
            $this->ajaxSuccess('添加成功');
        }
    }

    /**
     * 隐藏菜单
     * @author zhaoxiang <zhaoxiang051405@gmail.com>
     */
    public function close() {
        $id = I('post.id');
        $res = D('ApiMenu')->where(array('id' => $id))->save(array('hide' => 1));
        if ($res === false) {
            $this->ajaxError('操作失败');
        } else {
            $this->ajaxSuccess('添加成功');
        }
    }

    /**
     * 编辑菜单
     * @author zhaoxiang <zhaoxiang051405@gmail.com>
     */
    public function edit() {
        if (IS_GET) {
            $originList = D('ApiItem')->order('id asc')->select();
            $list = $this->buildArrByNewKey($originList);
            $listInfo = $list[I('get.id')];
            $options = array_column(formatTree(listToTree($originList)), 'name', 'id');

            $this->assign('detail', $listInfo);
            $this->assign('options', $options);
            $this->display('add');
        } elseif (IS_POST) {
            $postData = I('post.');
            $postData['hide'] = isset($postData['hide']) ? 1 : 0;
            $res = D('ApiItem')->where(array('id' => $postData['id']))->save($postData);
            if ($res === false) {
                $this->ajaxError('操作失败');
            } else {
                $this->ajaxSuccess('编辑成功');
            }
        }
    }

    public function del() {
        $id = I('post.id');
        $childNum = D('ApiMenu')->where(array('fid' => $id))->count();
        if ($childNum) {
            $this->ajaxError("当前菜单存在子菜单,不可以被删除!");
        } else {
            D('ApiMenu')->where(array('id' => $id))->delete();
            $this->ajaxSuccess('编辑成功');
        }
    }

}
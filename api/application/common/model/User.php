<?php
/*+++++++++++++++++++++++++++++
 * CopyRight yundieTech
 * User: mzq
 * Date: 2019/5/22 9:50
 * Doc: 用户管理
 *+++++++++++++++++++++++++++++
 */
namespace app\common\model;
use think\Model;

class User extends Model
{
    /**
     * 根据UID获取客户信息
     * @param $uid int
     * @return array
     * @throws
     */
    public function getUserInfoByUid($uid)
    {
        $map = ['a.uid' => $uid, 'a.status' => 1];
        $re = $this->alias('a')->where($map)
            ->join('user_extra b', 'a.uid=b.uid', 'left')
            ->join('auth_group_access c', 'a.uid=c.uid', 'left')
            ->field('a.uid,a.dp_id,a.account,a.uname,b.token,b.tk_uptime,b.tk_expire_time,c.group_id')
            ->find();
        return $re ? $re->toArray() : [];
    }

    public function getInfoByAccount($account)
    {
        return  $this->getInfoByMap(['a.account' => $account, 'a.status' => 1]);
    }


    public function getInfoByWxOpenid($openid)
    {
        return $this->getInfoByMap(['a.wx_openid' => $openid, 'a.status' => 1]);
    }

    private function getInfoByMap($map)
    {
        $re = $this->alias('a')->where($map)
            ->join('user_extra b', 'a.uid=b.uid', 'left')
            ->join('company c','a.dp_id = c.id','left')
            ->field('a.uid,a.dp_id,a.account,a.wx_openid,a.pwd,a.salt,a.uname,a.status,b.token,b.tk_uptime,b.tk_expire_time,c.name company_name')
            ->find();
        return $re ? $re->toArray() : [];
    }


    /**
     * @intro: 通过员工名称获取员工id
     * @param string $name
     * @return array
     */
    public function getUserId($name = '')
    {
        if (empty($name))
            return null;
        $where['uname'] = array('like', '%' . $name . '%');
        $where['status'] = 1;
        return $this->where($where)->column('uid');
    }

    /**
     * 获取用户列表
     * @param array $where,
     * @param string $page
     * @return array
     * @throws
     */
    public function getLists($where,$page)
    {
        $count = $this->alias('a')
                ->join('auth_group_access c', 'a.uid=c.uid', 'left')
                ->join('auth_group d','c.group_id=d.id','left')
                ->where($where)->count('a.uid');
        if($count == 0) {
            return  ['count'=>0,'lists'=>[]];
        }
        $lists = $this->alias('a')->where($where)
                ->join('auth_group_access c', 'a.uid=c.uid', 'left')
                ->join('auth_group d','c.group_id=d.id','left')
                ->field('a.uid,a.ctime,a.status,a.uname,a.account,a.position,c.group_id,d.title group_name')
                ->order('a.uid desc')
                ->page($page)
                ->select();
        return ['count'=>$count,'lists'=>collection($lists)->toArray()];
    }

    /**
     * 修改用户状态
     * @param int $id,int $old_status
     * @return int
     * @throws
     */
    public function changeStatus($id, $old_status)
    {
        return $this->where(array('uid' => $id))->setField('status', $old_status == 1 ? 0 : 1);
    }

    /**
     * 修改用户信息
     * @param array $data,int $id
     * @return int
     * @throws
     */
    public function setUserInfo($data, $id)
    {
        return $this->save($data, array('uid' => $id));
    }

    /**
     * 添加用户信息
     * @param array $param
     * @return int
     * @throws
     */
    public function addUser($param)
    {
        $this->startTrans();
        $salt = mt_rand(100000, 999999);
        $data = [
            'dp_id' => intval($param['dp_id']),
            'account' => $param['account'],
            'position' => $param['position'],
            'uname' => empty($param['uname']) ? $param['account'] : $param['uname'],
            'salt' => $salt,
            'status'=>1,
            'pwd' => make_pwd($param['pwd'], $salt),
            'ctime' => date('Y-m-d H:i:s')
        ];
        $uid=$this->insertGetId($data);
        if(!$uid){
            $this->rollback();
            return false;
        }
        //同时向user_extra添加数据
        $extra_model=new UserExtra();
        $re=$extra_model->add($uid);
        if(!$re){
            $this->rollback();
            return false;
        }
        $this->commit();
        return $uid;
    }

    /**
     * 修改用户信息
     * @param array $param,int $uid,string $pwd
     * @param $uid int
     * @param $pwd string
     * @throws
     * @return mixed
     */
    public function edit($param, $uid, $pwd = '')
    {
        $data = [
            'dp_id' => intval($param['dp_id']),
            'account' => $param['account'],
            'position' => $param['position'],
            'uname' => $param['uname'],
            'status' => intval($param['status'])
        ];
        if (!empty($pwd)) {
            $salt = mt_rand(100000, 999999);
            $data['salt'] = $salt;
            $data['pwd'] = make_pwd($pwd, $salt);
        }
        $re = $this->where(['uid' => $uid])->update($data);
        return  $re ? $uid : false;
    }

    /**
     * 更新用户绑定的微信openid
     * @param $uid int,$openid string
     * @return array
     * @throws
     */
    public function updateWxOpenid($uid,$openid)
    {
        return $this->where(['uid' => $uid])->update(['wx_openid'=>$openid]);
    }


    /**
     * 获取公司下面所有员工和角色信息
     * @param $where array
     * @return array
     * @throws
     */
    public function getUserInfo($where)
    {
        $info=$this->alias('a')
            ->where($where)
            ->join('auth_group_access b','a.uid=b.uid','left')
            ->field('a.status,b.group_id')
            ->select();
        return empty($info) ? [] : collection($info)->toArray();
    }

    /**
     * 获取角色分组
     * @param $where array
     * @return array
     * @throws
     */
    public function getUsersByGroup($where)
    {
        $lists = $this->alias('a')
            ->join('auth_group_access b','a.uid=b.uid','left')
            ->where($where)
            ->field('a.uid,a.uname')
            ->select();
        return  $lists ? collection($lists)->toArray() : [];
    } 

    // 根据条件查询用户姓名和Uid
    public function getUserName($where)
    {
        return $this->where($where)->field('uid,uname')->select();
    }

}
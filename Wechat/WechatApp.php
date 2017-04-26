<?php

namespace Wechat;

use Wechat\Lib\Common;
use Wechat\Lib\Tools;

/**
 * 微信菜单操作SDK
 *
 * @author Anyon <zoujingli@qq.com>
 * @date 2016/06/28 11:52
 */
class WechatApp extends Common {

    /** 修改服务器地址
     * https://open.weixin.qq.com/cgi-bin/showdocument?action=dir_list&t=resource/res_list&verify=1&id=open1489138143_WPbOO&token=cba3f0556dcf1028668f2b504de0ebf5ead5eaf0&lang=zh_CN
     */
    const APP_MODIFY_SERVER_URL = '/wxa/modify_domain?';

    /**
     * 上传小程序
     * https://api.weixin.qq.com/wxa/commit?access_token=TOKEN
     */
    const APP_UPLOAD_URL = '/wxa/commit?';


    /**
     * 绑定为体验者
     * https://api.weixin.qq.com/wxa/bind_tester?access_token=TOKEN
     */

    const APP_BIND_TESTER_URL = '/wxa/bind_tester?';

    /**
     *  解绑体验者
     * https://api.weixin.qq.com/wxa/unbind_tester?access_token=TOKEN
     */
    const APP_UNBIND_TESTER_URL = '/wxa/unbind_tester?';


    /**
     * 获取体验小程序的体验二维码
     * https://api.weixin.qq.com/wxa/get_qrcode?access_token=TOKEN
     */
    const APP_TESTER_QRCODE_URL = '/wxa/get_qrcode?';

    /**
     * 提交审核
     */
    const APP_POST_FOR_AUDIT_URL = '/wxa/submit_audit?';


    /**
     * 发布审核通过的版本
     */
    const APP_RELEASE_URL = "/wxa/release?";

    /**
     * 获取session_key
     */
    const APP_SESSION_KEY_URL = "/sns/component/jscode2session?";



    /**
     * 修改服务器地址
     * https://api.weixin.qq.com/wxa/modify_domain?access_token=TOKEN
     * POST数据示例：
            {
                "action":"add",
                "requestdomain":["https://www.qq.com","https://www.qq.com"],
                "wsrequestdomain":["wss://www.qq.com","wss://www.qq.com"],
                "uploaddomain":["https://www.qq.com","https://www.qq.com"],
                "downloaddomain":["https://www.qq.com","https://www.qq.com"],
            }
     *
     * 返回说明（正常时返回的json示例）：
            {
                "errcode":0,
                "errmsg":"ok",
                //以下字段仅在get时返回
                "requestdomain":["https://www.qq.com","https://www.qq.com"],
                "wsrequestdomain":["wss://www.qq.com","wss://www.qq.com"],
                "uploaddomain":["https://www.qq.com","https://www.qq.com"],
                "downloaddomain":["https://www.qq.com","https://www.qq.com"],
            }
     */
    public function modifyServer($data){
        if (!$this->access_token && !$this->getAccessToken()) {
            return false;
        }

        $result = Tools::httpPost(self::API_BASE_URL_PREFIX . self::APP_MODIFY_SERVER_URL . "access_token={$this->access_token}", Tools::json_encode($data));
        if ($result) {
            $json = json_decode($result, true);
            debug("modifyServer result:".var_export($json,true));
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return $this->checkRetry(__FUNCTION__, func_get_args());
            }
            return true;
        }
        return false;
    }

    /**
     * 绑定为小程序的体验者
     * @param $wechatId
     */
    public function bindTester($wechatId){
        if (!$this->access_token && !$this->getAccessToken()) {
            return false;
        }

        $data = array(
            "wechatid"=>$wechatId,
        );

        $result = Tools::httpPost(self::API_BASE_URL_PREFIX . self::APP_BIND_TESTER_URL . "access_token={$this->access_token}", Tools::json_encode($data));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return $this->checkRetry(__FUNCTION__, func_get_args());
            }
            return true;
        }
        return false;
    }

    /**
     * 解绑体验者
     * @param $wechatId
     * @return bool|mixed
     */
    public function unBindTester($wechatId){
        if (!$this->access_token && !$this->getAccessToken()) {
            return false;
        }

        $data = array(
            "wechatid"=>$wechatId,
        );

        $result = Tools::httpPost(self::API_BASE_URL_PREFIX . self::APP_UNBIND_TESTER_URL . "access_token={$this->access_token}", Tools::json_encode($data));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return $this->checkRetry(__FUNCTION__, func_get_args());
            }
            return true;
        }
        return false;
    }




    /**
     * 为授权的小程序帐号上传小程序代码
     * https://open.weixin.qq.com/cgi-bin/showdocument?action=dir_list&t=resource/res_list&verify=1&id=open1489140610_Uavc4&token=cba3f0556dcf1028668f2b504de0ebf5ead5eaf0&lang=zh_CN
     *
     * https://api.weixin.qq.com/wxa/commit?access_token=TOKEN
        POST数据示例:
        {
            "template_id":0,
            "ext_json":"JSON_STRING", //ext_json需为string类型，请参考下面的格式
            "user_version":"V1.0",
            "user_desc":"test",
        }
     */
    public function uploadApp($data){
        if (!$this->access_token && !$this->getAccessToken()) {
            return false;
        }

        $result = Tools::httpPost(self::API_BASE_URL_PREFIX . self::APP_UPLOAD_URL . "access_token={$this->access_token}", Tools::json_encode($data));
        if ($result) {
            debug("upload result:".var_export($result,true));
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return $this->checkRetry(__FUNCTION__, func_get_args());
            }
            return true;
        }
        return false;
    }

    /**
     * 获取体验二维码
     * https://api.weixin.qq.com/wxa/get_qrcode?access_token=TOKEN
     */
    public function getTestQrCode(){
        if (!$this->access_token && !$this->getAccessToken()) {
            return false;
        }

        $result = Tools::httpGet(self::API_BASE_URL_PREFIX . self::APP_TESTER_QRCODE_URL . "access_token={$this->access_token}", Tools::json_encode($data));
        if ($result) {
            //debug("qrcode result:".var_export($result,true));
            /*$json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return $this->checkRetry(__FUNCTION__, func_get_args());
            }*/
            debug("qrcode result:image");
            return $result;
        }
        return false;
    }


    /**
     * 提交审核
     * @return bool|mixed
     */
    public function postForAudit($data){
        if (!$this->access_token && !$this->getAccessToken()) {
            return false;
        }

        $result = Tools::httpPost(self::API_BASE_URL_PREFIX . self::APP_POST_FOR_AUDIT_URL . "access_token={$this->access_token}", Tools::json_encode($data));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return $this->checkRetry(__FUNCTION__, func_get_args());
            }
            return true;
        }
        return false;
    }


    /**
     * 发布审核通过的版本
     * @return bool|mixed
     */
    public function release(){
        if (!$this->access_token && !$this->getAccessToken()) {
            return false;
        }

        $result = Tools::httpPost(self::API_BASE_URL_PREFIX . self::APP_RELEASE_URL . "access_token={$this->access_token}", Tools::json_encode("{}"));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return $this->checkRetry(__FUNCTION__, func_get_args());
            }
            return true;
        }
        return false;
    }





}

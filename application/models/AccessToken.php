<?php

class AccessToken extends MY_Model 
{   
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Apilib');
        $this->load->library('Untils');
    }

    public static function getTableName()
    {
        return 'access_tokens';
    }

    public static function getPrimaryKey()
    {
        return SELF::DEFAULT_PRIMARY_KEY;
    }

    public function relations()
    {
        return [
            'user' => ['User', 'id', SELF::HAS_ONE, 'user_id']
        ];
    }

    public static function createSessionToken(array $data)
    {   
        $tableName = self::getTableName();
        $data['expiry'] = time() + Apilib::SESSION_LIFESPAN;
        $query = ($db=DB_Helper::getDb())->insert_string($tableName, $data);
        $query .= ' ON DUPLICATE KEY update `token` = VALUES(`token`), `expiry` = VALUES(`expiry`)';
        $success = $db->query($query);
        return $success ? true : false;
    }

    public static function validateToken($accessToken, $userId, $appId)
    {
        $token = self::findOne([
            'user_id' => $userId, 
            'token' => $accessToken, 
            'app_id' => $appId
        ]);
        if (is_null($token)) {
            return null;
        }
        if (
            ($token->expiry == 0 || 
            $token->expiry > time()) &&
            strcasecmp($accessToken, $token->token) === 0
        ) {
            $token->expiry = time() + Apilib::SESSION_LIFESPAN;
            $token->save();
            return true;
        } 
        return false;
    }

    public static function findATUserId($user_id){
        return SELF::findOne(['user_id'=>$user_id]);
     }
}
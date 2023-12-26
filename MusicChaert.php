<?php
class MusicChaert{
    
    private $PLATFORM = "VIBE";
    private $ALLOWD_VENDOR = ["VIBE", "MELON", "BUGS"];
    private $_FETCH_ENDPOINT = "https://api.winsub.kr/MUSIC-CHART";
    private $_FETCH_METHOD = "GET";

    public function __construct($PLATFORM="VIBE"){
        $this->PLATFORM = $PLATFORM;
    }

    private function RETURN($STATUS, $MSG = null, $STATUS_CODE = null, $DATA = null){
        $RETURN = array("STATUS" => $STATUS === "OK" ? "OK" : "ERR");
        if ($STATUS_CODE) $RETURN["CODE"] = $STATUS_CODE;
        if ($MSG) $RETURN["MSG"] = $MSG;
        if ($DATA) $RETURN["DATA"] = $DATA;
        return $RETURN;
    }

    public function GET_CHAERT($DATE=null){
        if(!is_null($DATE)) $DATE = date("Y-m-d", strtotime($DATE));
        if(!in_array($this->PLATFORM, $this->ALLOWD_VENDOR)) return self::RETURN("ERR", "지원하지 않는 플렛폼이에요 생성자를 확인해주세요", "NOT_ALLOWD_PLATFORM");
        $CURL = curl_init();
        curl_setopt($CURL, CURLOPT_URL, $this->_FETCH_ENDPOINT."/{$this->PLATFORM}?DATE={$DATE}"); // Date기능은 현재 API에서 지원하지 않습니다.
        curl_setopt($CURL, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($CURL, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($CURL, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($CURL, CURLOPT_CUSTOMREQUEST, $this->_FETCH_METHOD);
        $RESPONSE = curl_exec($CURL);
        curl_close($CURL);
        $HTTP_CODE = curl_getinfo($CURL, CURLINFO_HTTP_CODE);
        if($HTTP_CODE !== 200) return self::RETURN("ERR", "통신을 실패했어요 ({$HTTP_CODE})", "CURL_FAIL");
        $DECODE = json_decode($RESPONSE, true);
        if(!is_array($DECODE)) return self::RETURN("ERR", "데이터 파싱을 실패했어요", "DATA_PARSE_FAIL");
        if($DECODE['STATUS'] !== "OK") return self::RETURN("ERR", $DECODE['MSG'], $DECODE['CODE']);
        return self::RETURN("OK", $DECODE['MSG'], array("VENDOR" => $this->PLATFORM, "LIST" =>$DECODE['DATA']));
    }
}

<?php


class SotmarClient
{
    /**
    * @var string адрес прокси-сервера (если используется)
    */
    var $sProxyAddress = NULL;

    /**
    * @var integer порт прокси-сервера (если используется)
    */
    var $iProxyPort = NULL;

    /**
    * @var array массив строк - заголовков запроса HTTP
    */
    var $aRequestHeaders;

    /**
    * @var array массив строк - заголовков ответа HTTP
    */
    var $aResponseHeaders = array();

    /**
    * @var boolean сжимать ли контент. Сжатие происходит только при наличии модуля ZLIB
    */
    var $bCanCompress = false;

    /**
    * Конструктор
    */
    function wkSotmarClient()
    {
        $this->aRequestHeaders = array(
            'User-Agent'  => $_SERVER['HTTP_USER_AGENT'],
            'IPclient'    => sotmar_ip_det_sm(),
            'Connection'  => 'Keep-Alive',
        );
    }

    /**
    * Функция устанавливает заголовки, переопределяя установленные ранее.
    * @param array $aHeaders [in] массив заголовков
    * @return integer true
    */
    function SetHeaders($aHeaders)
    {
        $this->aRequestHeaders = array_merge($this->aRequestHeaders, $aHeaders);
        return true;
    }

    /**
    * Устанавливает параметры Basic-аунтификации HTTP
    * @param string $sUser     [in] username
    * @param string $sPassword [in] password
    * @return integer true
    */
    function SetAuthParams($sUser, $sPassword)
    {
        $tmp = base64_encode("$sUser:$sPassword");
        $this->SetHeaders(array('Authorization' => 'Basic '.$tmp)); 
        return true;
    }

    /**
    * Устанавливает адрес Proxy
    * @param string $sAddress [in] адрес прокси-сервера
    * @param integer $iPort   [in] порт прокси-сервера
    * @return integer true
    */
    function SetProxy($sAddress, $iPort = 8080)
    {
        $this->sProxyAddress = $sAddress; 
        $this->iProxyPort    = $iPort; 
        return true;
    }

    /**
    * Устанавливает параметры Proxy-аунтификации
    * @param string $sUser     [in] username
    * @param string $sPassword [in] password
    * @return integer true
    */
    function SetProxyAuthParams($sUser, $sPassword)
    {
        $tmp = base64_encode("$sUser:$sPassword");
        $this->SetHeaders(array('Proxy-Authorization' => 'Basic '.$tmp)); 
        return true;
    }

    /**
    * Функция выполняет запрос HTTP 1.1 GET
    * 
    * @global class использует $wk_logger
    * @param string $sHost      [in] доменное имя сервера
    * @param string $sUrl       [in] путь к файлу на сервере (напр. '/webkarta/disp_js?qwe=3')
    * @param string $iPort      [in] порт сервера, опциональный параметр
    * @return mixed http response string on succeed or wkError   
    */
    function Get($sHost, $sUrl, $iPort = 80)
    {
        $req_str = "GET http://$sHost/$sUrl HTTP/1.0\r\n"
        ."Host: $sHost\r\n";

        if (is_array($this->aRequestHeaders)){
            foreach($this->aRequestHeaders as $k => $v)
            {
                $req_str .= "$k: $v\r\n";
            }
        }

        // включить сжатие, если поддерживается
        $bCompress = (boolean)extension_loaded('zlib') && $this->bCanCompress;
        if ($bCompress)
        {
            $req_str .= "Accept-Encoding: gzip,deflate\r\n";
        } 

        $req_str .= "\r\n";  

        // открываем сокет
        $sock_errno  = 0;
        $sock_errstr = '';

        // выбираем, работать напрямую или через прокси
        if (empty($this->sProxyAddress))
        {
            $h = $sHost;
            $p = $iPort;
        } 
        else
        {
            $h = $this->sProxyAddress;
            $p = $this->iProxyPort;
        }

        $sock = @fsockopen($h, $p, $sock_errno, $sock_errstr, 30);
        if (FALSE === $sock) {
            return false;
        } 
        

        // посылаем заголовок HTTP
        $res = fwrite($sock, $req_str);
        if (strlen($req_str) != $res) {
            return false;
        }
        

        //читаем построчно заголовок ответа HTTP
        $bEndOfHeader = false;

        while (!feof($sock) && !$bEndOfHeader)
        {
            $s = fgets($sock, 1024);
            $this->aResponseHeaders[] = $s; //todo split by :

            // Get Content Length
            $param = stristr($s, 'Content-length: ');
            if (is_string($param))
            {
                $content_length = (integer)substr($s, strlen('Content-length: '));
            }

            // Get Compression Method
            $param = stristr($s, 'Content-encoding: ');
            if (is_string($param))
            {
                $compress_method = trim( substr($s, strlen('Content-encoding: ')));
            }

            // признак конца заголовка 
            if ("\r\n" == $s) $bEndOfHeader = true;
        }

        // читаем данные
        $res = '';
        while (!feof($sock))
        {
            $tmp = fread($sock, 128);
            $res .= $tmp;
        }  

        // закрываем сокет
        fclose($sock);

        // если контент сжат расжать
        if (isset($compress_method))
        {
            switch ($compress_method)
            {
                case 'gzip' :
                    $res = gzuncompress($res);
                    break;

                case 'deflate' :
                    $res = gzinflate($res);
                    break;
                    
                default:
                    return 1;        
            }
        }
        return $res;
    }
}

<?php
class iPrice
{
    private $slug, $country = false;
    private $endpoint = '/api/offers/';
    private $countryList = [
        'HK' => 'https://iprice.hk',
        'ID' => 'https://iprice.co.id',
        'MY' => 'https://iprice.my',
        'PH' => 'https://iprice.ph',
        'SG' => 'https://iprice.sg',
        'TH' => 'https://ipricethailand.com',
        'VN' => 'https://iprice.vn',
        'AU' => 'https://iprice.au',
    ];

    public function setSlug($str){
        $this->slug = $str;
    }
    public function setCountry($str){
        if(!isset($this->countryList[$str])) throw new Exception("make sure the country is either HK, ID, MY, PH, SG, TH, VN, or AU", 1);
        $this->country = $this->countryList[$str];
    }

    public function get_verified(){
        $this->all_set(); //check setter

        $this->verified = 'true';
        return $this->fetch();
    }
    public function get_unverified(){
        $this->all_set(); //check setter

        $this->verified = 'false';
        return $this->fetch();
    }

    private function all_set(){
        if(!$this->slug) throw new Exception("Please set slug first | setSlug()", 1);
        if(!$this->country) throw new Exception("Please set country first | setCountry()", 1);
        return;
    }

    private function fetch(){
        $uniq = uniqid();

        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $this->country.$this->endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, <<<POST_DATA
        ------WebKitFormBoundary{$uniq}
        Content-Disposition: form-data; name="slug"

        $this->slug
        ------WebKitFormBoundary{$uniq}
        Content-Disposition: form-data; name="section"

        others
        ------WebKitFormBoundary{$uniq}
        Content-Disposition: form-data; name="page_type"

        model
        ------WebKitFormBoundary{$uniq}
        Content-Disposition: form-data; name="isVerified"

        $this->verified
        ------WebKitFormBoundary{$uniq}
        Content-Disposition: form-data; name="includeNonPurchasable"

        true
        ------WebKitFormBoundary{$uniq}
        Content-Disposition: form-data; name="_after"

        
        ------WebKitFormBoundary{$uniq}
        Content-Disposition: form-data; name="rawLowestPrice"

        0
        ------WebKitFormBoundary{$uniq}
        Content-Disposition: form-data; name="position"

        1
        ------WebKitFormBoundary{$uniq}--'
        POST_DATA);
        
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        
        $headers = array();
        $headers[] = "Content-Type: multipart/form-data; boundary=----WebKitFormBoundary{$uniq}";
        $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        $result = @json_decode(curl_exec($ch));
        if (curl_errno($ch)) throw new Exception(curl_error($ch), 1);
        curl_close($ch);
        if(!$result) throw new Exception("cannot parse data", 1);
        
        print_r($result);
        return $result;
    }
}


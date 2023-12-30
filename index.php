<?php
require('iPrice.php');
try {
    $p = new iPrice();
    $p->setCountry('ID'); // set Country: HK, ID, MY, PH, SG, TH, VN, or AU
    $p->setSlug('philips-hd117299'); // set iPrice slug: "https://iprice.co.id/harga/sony-playstation-3-slim-white-250gb/"

    $verified_data = $p->get_verified();
    $unverified_data = $p->get_unverified();
} catch (\Throwable $th) {
    echo $th->getMessage();
}
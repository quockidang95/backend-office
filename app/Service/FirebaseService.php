<?php

namespace App\Services;

use Exception;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Exception\Auth\EmailExists as FirebaseEmailExists;

class FirebaseService
{
    /**
     * @var Firebase
     */
    protected $firebase;

    public function __construct()
    {
        $serviceAccount = ServiceAccount::fromArray([

                "type" => "service_account",
                "project_id" => "officecoffee-72b42",
                "private_key_id" => "6fe6f595e1309c81c972052dff6dbf8cc46ce500",
                "private_key" => "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDCK0U3Dc+q3901\nD2OfCLF2WKQTSFuvB6AQx5lkfdVewbyKepY3uz2qsGI1hNx8VjQ5EZrWS5EIFfkO\nFMOFa1XHeU+CqY7DrAmvj9D/xzh7u1w5ZzlxmXb1kYOomnojxhslu8Y00AixzJlF\np80ectRuVofddO6DMwbP4eHNvQa4AX036SOsK+35Z3LhXkv03QJCS0vQZlKP0WnW\njnulXUkZKaTwbyDroOgK5Q44pc1QmbP4fvgXYWKg/feerpwYJwQq9tHoQWWm3+No\njTt/avCt9B2HwFK8bsR5CMIZTi4lrnNdrceShSbdpAQmpQfv6Kuaxbz/j7Ndwtgx\nE1zeV1YHAgMBAAECggEAAfljUcT6gzfqzYWjiKlo9yq9jaijA1Y7grrUzMMZbpuK\nVDTHiYzbeqcicygvqbVNVfXfh3rrNZQrnRYJoDX61aAJIUCiFUvkKVHNWTG9rvG1\nEAGvAYBYR5+Rdsm4oNvRyNduPiPjWBNxd4DWQrn+KO+zn+/MFloSy/2gSHGPO5hb\nvxVSKboUri1npTQcCfdxuIh3/U+Or2Jn//o2LGMe+Ui4MWentwT4sl2m8cVu1KuD\nktrif2yotRl9mdGHWB3r+O5HUNeTysxcbKvNEhNk77g2v94sxMeFl5hIzeC4ePKh\nqwEM6MKZQuTeJ5z+lVTqC0xOW/W0Jz+ZJhWszehI+QKBgQDjEWIJbwtu4cextCD3\nerFIjyOnGFDZ7OE3RuMCfCy/FCqC/Sq581TXt66eIXr3GAG+whSwwQPKPhQ2VQuE\nz/vmHQAjN/ahOYaqwxkvZEp+CWjKvw0hkeOrHHYgxwS5u7yEijPskwOWEswUztOI\n5wz4UbaLO0EEQdnfSDmeyAP8DQKBgQDa6MZmSloYzEol24A23X2tq6eBttlpKcEt\nrOpLd9X8vMIecdpRtU/i/SXMY9xbwJfdQDpgECBUeiUJJBCF7/VL1H6XdnK8V8Zf\n0lEvXvM8jlTYETaY6mZUe3vPO+4jegqKb5DwtrLcrFCNcgOfLnIoxEAeU7cDQ/Yj\na6vwMVARYwKBgDwCVA56MmNTeBsWp7+/xSqP6bQ3UObHG54oNcZqWskE41dK4QfT\nrxBzkspnvr+esJdhY2ZzHDVOwri5MRogfUoLUAX+vEdPlK98cCN+NdGpWvB6HVZJ\n4AlQtEdlUXJkkyXUAHT9RW1b+mNlZbJAncTik4OoXT5qyNGhnNjBs4vhAoGACc24\n7OXRiSDFBVMCN/LNaRSx6L4+mFYVX61JxN12CN6PNvvUUipzjG/X3IJwYO+MHY19\nz8WJHR9nPzSNROJNMvsuPCxC0SqBKNNrbVqsO/8Hz7Fdp5V/xbLOBabicFZL2I1M\niWs1MWrKJLuBbASRej/nuzP2kz/c6xMsXRJoSo8CgYEAqF6s6sAjlKKDzNw8hv/u\nATCthpANsONretYfpthynzCHVnrLXZWFjwl+zMLLENSivmI5/YPItAkRg3z3CrA7\ncc5Hg16/Vc0UnI8LYvX2mmvQiLvpKq+ARLiYqB6w4KJv8CJHmwJyM+Wk95rzOgGO\nVZL94TphtS3cg0smXY+otlY=\n-----END PRIVATE KEY-----\n",
                "client_email" => "firebase-adminsdk-nu548@officecoffee-72b42.iam.gserviceaccount.com",
                "client_id" => "117623230656087553219",
                "auth_uri" => "https://accounts.google.com/o/oauth2/auth",
                "token_uri" => "https://oauth2.googleapis.com/token",
                "auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",
                "client_x509_cert_url" => "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-nu548%40officecoffee-72b42.iam.gserviceaccount.com"


        ]);

        $this->firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->create();
    }
}

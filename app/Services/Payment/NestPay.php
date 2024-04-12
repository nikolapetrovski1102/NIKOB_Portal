<?php

namespace App\Services\Payment;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Config;

class NestPay
{
    const TEST_URL = 'https://entegrasyon.asseco-see.com.tr/fim/est3Dgate';
    const PROD_URL = 'https://epay.halkbank.mk/fim/est3Dgate';
    
    private string  $url = '',
                    $storekey = 'SKEY9144',
                    $clientID = '180000104',
                    $storeType = '3D_PAY_HOSTING';

    public string   $lang = 'mk',
                    $transactionType = 'Auth',
                    $callbackURL = '/pay/callback',
                    $currency = '807',
                    $rnd,
                    $okUrl,
                    $failUrl,
                    $orderID;
    public float    $amount;
                    

    function __construct() {
        $this->setUrl();

        $this->amount = 0.00;
        $this->rnd = microtime();

        
    }
    
    private function setUrl() {
        if(env('APP_DEBUG')) {
            $this->url = self::TEST_URL;
        } else {
            $this->url = self::PROD_URL;
        }
    }

    public function form(): string
    {
        ob_start();
		$this->generateForm();
		return ob_get_clean();
    }

    /**
	 * 3D secure form that automatically submits.
	 *
	 * @return void
	 */
    public function __set($name, $value){
        $classVar = get_object_vars($this);
        if(in_array($name, $classVar)){
            $this->$name = $value;
        }
    }

    public function validate(Request $request): bool {
        // Make sure we don't get any error reported.
		error_reporting(0);
		if( isset( $request->get['order'] ) && ! empty( $request->get['order'] ) ) {
			$this->orderID = intval( $request->get['order'] );
		} else {
            // throw error
		}

		$storekey = $this->storekey;

		$hashparams = $request->post["HASHPARAMS"];
		$hashparamsval = $request->post["HASHPARAMSVAL"];
		$hashparam = $request->post["HASH"];
		$paramsval = '';
		$index1 = 0;
		$index2 = 0;

		while($index1 < strlen( $hashparams ) ) {
			$index2 = strpos( $hashparams, ":", $index1 );
			$vl = $request->post[substr( $hashparams, $index1, $index2 - $index1 )];
			if( $vl == null ) {
				$vl = '';
			}
			$paramsval = $paramsval . $vl;
			$index1 = $index2 + 1;
		}
		$hashval = $paramsval . $storekey;
		$hash = base64_encode( pack( 'H*', sha1( $hashval ) ) );

		if ( $hashparams != null ) {
			if ($paramsval != $hashparamsval || $hashparam != $hash) {
                return false;
			} else {
				if (in_array($request->post['mdStatus'], [ '1', '2', '3', '4'])) {
					return $request->post["Response"] == "Approved" ? true : false;
				} else {
                    return false;
				}
			}
		} else {
            return false;
		}
    }

    /**
	 * 3D secure form that automatically submits.
	 *
	 * @return void
	 */
	private function generateForm(){
		?>
            <?php
                $this->okUrl = env('APP_URL').$this->callbackURL.'?order=' . $this->orderID.'&status=ok';
                $this->failUrl = env('APP_URL').$this->callbackURL.'?order=' . $this->orderID.'&status=fail';

                $hash = $this->generateHash();
            ?>
            <form name="form" id="3d-secure-form" action="<?php echo $this->url;?>" method="POST">
                <input type="hidden" name="clientid" value="<?php echo $this->clientID; ?>" />
                <input type="hidden" name="amount" value="<?php echo $this->amount; ?>" />
                <input type="hidden" name="islemtipi" value="<?php echo $this->transactionType; ?>" />
                <input type="hidden" name="oid" value="<?php echo $this->orderID; ?>" />
                <input type="hidden" name="okUrl" value="<?php echo $this->okUrl; ?>" />
                <!-- <input type="hidden" name="callbackUrl" value="<?php echo $this->okUrl; ?>" /> -->
                <input type="hidden" name="failUrl" value="<?php echo $this->failUrl; ?>" />
                <input type="hidden" name="rnd" value="<?php echo $this->rnd; ?>" />
                <input type="hidden" name="hash" value="<?php echo $hash; ?>" />
                <input type="hidden" name="storetype" value="<?php echo $this->storeType; ?>" />
                <input type="hidden" name="lang" value="<?php echo $this->lang; ?>" />
                <input type="hidden" name="currency" value="<?php echo $this->currency; ?>" />
                <input type="hidden" name="refreshtime" value="0" />
            </form>
            <script type="text/javascript">
                window.onload=function(){
                    document.forms["3d-secure-form"].submit();
                }
            </script>
		<?php
	}

    private function generateHash(): string {
        $hashstr = $this->clientID . $this->orderID . $this->amount . $this->okUrl . $this->failUrl .$this->transactionType. $this->rnd . $this->storekey;

        return base64_encode(pack('H*',sha1($hashstr)));
    }

}

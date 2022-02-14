<?php

	namespace PA\Helper;

	Class Helper
	{

		public function geraCODRandNumber($n)
		{
			return strtoupper( substr(uniqid(mt_rand()), 0, $n) );
		}

		public function geraCODRand($n)
		{
			return strtoupper( substr(md5(uniqid(time())), 0, $n) );
		}

		public function secured_decrypt($ciphertext)
		{
            $password = "e4X412AfCJv247";
            return openssl_decrypt(base64_decode($ciphertext), "AES-128-ECB", $password);
		}



	}
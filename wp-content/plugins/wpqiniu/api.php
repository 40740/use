<?php
//	if(version_compare(PHP_VERSION,'5.3.0', '<')){
//		echo '当前版本为'.phpversion().'小于5.3.0哦';
//	}else {
//		echo '当前版本为' . PHP_VERSION . '大于5.3.0';
//	}
	require 'sdk/autoload.php';
	
	use \Qiniu\Auth;
	use \Qiniu\Storage\UploadManager;	// 引入上传类
	use \Qiniu\Storage\BucketManager;


	class QiNiuApi
	{
		// 用于签名的公钥和私钥
		private $bucket;  // = 'laojiang';
		private $token_expires = 3600;  // Token 的超时时间。
		protected $auth;
		private $cache_path;

		public function __construct($option) {
			$this->bucket = $option['bucket'];
			// 初始化签权对象
			$this->auth = new Auth($option['accessKey'], $option['secretKey']);

			$this->cache_path = join(DIRECTORY_SEPARATOR, [plugin_dir_path(__FILE__ ), 'access_token.json']);
		}

		public function uploadToken() {
			$uploadToken = json_decode(file_get_contents($this->cache_path), true);
			$c_time = time();
			if (empty($uploadToken) or empty($uploadToken['access_token']) or $c_time > $uploadToken['expires']) {
				$token = $this->auth->uploadToken($this->bucket, null, $this->token_expires);
				$uploadToken = [
				    'access_token' => $token,
                    'expires'      => $c_time + 3600,
                ];
				file_put_contents($this->cache_path, json_encode($uploadToken), LOCK_EX);
			}
			return $uploadToken['access_token'];
		}

		public function Upload($key, $localFilePath) {
			// 构建鉴权对象
			// 生成上传 Token
			$token = $this->uploadToken();

			// 初始化 UploadManager 对象并进行文件的上传。
			$uploadMgr = new UploadManager();
			// 调用 UploadManager 的 putFile 方法进行文件的上传。
			list($ret, $err) = $uploadMgr->putFile($token, $key, $localFilePath);
			if ($err !== null) {
//				var_dump($err);
				return False;
			} else {
//				var_dump($ret);
				return True;
			}
		}

		public function Delete($keys) {
			$config = new \Qiniu\Config();
			$bucketManager = new BucketManager($this->auth, $config);
			//每次最多不能超过1000个
			$ops = $bucketManager->buildBatchDelete($this->bucket, $keys);
			list($ret, $err) = $bucketManager->batch($ops);
			if ($err) {
//				print_r($err);
				return False;
			} else {
//				print_r($ret);
				return True;
			}
		}

		public function hasExist($key) {
			$config = new \Qiniu\Config();
			$bucketManager = new \Qiniu\Storage\BucketManager($this->auth, $config);
			list($fileInfo, $err) = $bucketManager->stat($this->bucket, $key);
			if ($err) {
//				print_r($err);
				return False;
			} else {
//				print_r($fileInfo);
				return True;
			}
		}

	}

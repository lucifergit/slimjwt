
Slimjwt
=======

How to use?

```php
	//include Slimjwt.php to your project
	//加密秘钥
	$secret = 'your-secret';
	//载荷 可自定义内容，解密后返回完整数组 exp为过期时间戳 过期后解密返回false
	$payload =array(
            'userid'=>1,
	    'exp'=>time()+60*60*6
	);
	//生成jwt
	$jswstr = Slimjwt::encode($payload,$secret);
	//解密验证jwt
	var_dump(Slimjwt::decode($jswstr,$secret));
```

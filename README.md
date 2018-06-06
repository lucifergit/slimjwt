#SlimJwt
#a Slim php jwt 

##How to use?
```php
		$this->load->helper('jwt');
		$secret = 'your-secret';
		$payload =array(
		'uid'=>001,
		'exp'=>time()+60*60*6,
		'nbf'=>time()-5*60
		);
		$jswstr = Slimjwt::encode($payload,$secret);
		var_dump(Slimjwt::decode($jswstr,$secret));
```

Slimjwt
=======

How to use?

```php
	//include Slimjwt.php to your project
	$secret = 'your-secret';
	$payload =array(
	    'exp'=>time()+60*60*6
	);
	$jswstr = Slimjwt::encode($payload,$secret);
	var_dump(Slimjwt::decode($jswstr,$secret));
```

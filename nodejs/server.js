var Memcached = require('memcached');
var memcached = new Memcached('localhost:11711');
var mysql      = require('mysql');
var connection = mysql.createConnection({
	host     : 'localhost',
	user     : 'mysql_user',
	password : 'password'
});

function implode( glue, pieces ) {	// Join array elements with a string
	// 
	// +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// +   improved by: _argos
	
	return ( ( pieces instanceof Array ) ? pieces.join ( glue ) : pieces );
}

connection.connect(function(err) {
	console.log(err);
});

connection.query('use crypto');
var zlib = require('zlib');
var http = require('http');
var fs = require('fs');
var app = http.createServer(function (req, res) {
	
	var $get = req.url.split(/\//);
	console.log(req.url.split(/\//));
	
	var raw = [];
	var acceptEncoding = req.headers['accept-encoding'];
	if (!acceptEncoding) {
		acceptEncoding = '';
	}
	switch($get[1])
	{
		case 'profit_24':
		var crypto_id = parseInt($get[2]);
		if(crypto_id != 'NaN')
		{
			memcached.get('profit_24_'+crypto_id, function (err, data) {
				if(err) throw err;
				
				if(!data)
				{
					console.log('getting from db');
					connection.query('select percent, time from profits where crypto_id = '+crypto_id+' and time >= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 24 HOUR))', function(err, rows) {
						if(err) throw err;
						
						console.log("count = "+rows.length);
						
						for(doc in rows)
						{
							if(doc !== null)
							{
								//console.log(rows[doc]);
								raw.push("["+rows[doc].time+"000,"+rows[doc].percent+"]");
							}
						}
						
						var response = "["+implode(',',raw)+"]";
						memcached.set('profit_24_'+crypto_id, response, 300, function (err) { console.log(err); });
						output(response);
					});
				}
				else
				{
					console.log('cached');
					output(data);
				}
				
			});
		}
		else
		{
			console.log('NaN');
			output('nothing to do here');
		}
		break;
		
		case 'price_full':
		var crypto_id = parseInt($get[2]);
		if(crypto_id != 'NaN')
		{
			memcached.get('price_full_'+crypto_id, function (err, data) {
				if(err) throw err;
				
				if(!data)
				{
					console.log('getting from db');
					connection.query('select avg(price) as price,time,FROM_UNIXTIME(time, "%m.%d.%Y %H:00:00") as date, exchange from prices where crypto_id = '+crypto_id+' group by date asc order by date asc', function(err, rows) {
						if(err) throw err;
						
						console.log("count = "+rows.length);
						var exchanges = {'allcoin': 'AllCoin.com', 'cryptorush': 'CryptoRush.in', 'poloniex': 'Poloniex.com', 'mintpal': 'MintPal.com', 'cryptsy': 'Cryptsy.com', 'bter': 'Bter.com', 'xnigma': 'Xnigma.com', 'bittrex': 'Bittrex.com', 'bleutrade': 'Bleutrade.com'};
						
						for(doc in rows)
						{
							if(doc !== null)
							{
								//console.log(rows[doc]);
								raw.push('{"x": '+rows[doc].time+'000,"y": '+rows[doc].price+', "exchange": "'+exchanges[rows[doc].exchange]+'"}');
								
							}
						}
						
						var response = "["+implode(',',raw)+"]";
						memcached.set('price_full_'+crypto_id, response, 300, function (err) { console.log(err); });
						output(response);
					});
				}
				else
				{
					console.log('cached');
					output(data);
				}
				
			});
		}
		else
		{
			console.log('NaN');
			output('nothing to do here');
		}
		break;
		
		case 'profit_full':
		var crypto_id = parseInt($get[2]);
		if(crypto_id != 'NaN')
		{
			memcached.get('profit_full_'+crypto_id, function (err, data) {
				if(err) throw err;
				
				if(!data)
				{
					console.log('getting from db');
					connection.query('select percent, time,FROM_UNIXTIME(time, "%m.%d.%Y %H:00:00") as date from profits where crypto_id = '+crypto_id+' group by date asc order by date asc', function(err, rows) {
						if(err) throw err;
						
						console.log("count = "+rows.length);
						
						for(doc in rows)
						{
							if(doc !== null)
							{
								//console.log(rows[doc]);
								raw.push("["+rows[doc].time+"000,"+rows[doc].percent+"]");
							}
						}
						
						var response = "["+implode(',',raw)+"]";
						memcached.set('profit_full_'+crypto_id, response, 300, function (err) { console.log(err); });
						output(response);
					});
				}
				else
				{
					console.log('cached');
					output(data);
				}
				
			});
		}
		else
		{
			console.log('NaN');
			output('nothing to do here');
		}
		break;
	}
	
	function output(response)
	{
		if (acceptEncoding.match(/\bgzip\b/)) {
			res.writeHead(200, {'Content-Type': 'text/plain', 'content-encoding': 'gzip','Access-Control-Allow-Origin':'*' });
			zlib.gzip(response, function (_, result) {  // The callback will give you the 
				res.end(result);                     // result, so just send it.
			});
			} else {
			res.writeHead(200, {'Content-Type': 'text/plain','Access-Control-Allow-Origin':'*'});
			res.end(response);
		}
	}
	
	//res.end("<h1>hello</h1>");
}).listen(90);
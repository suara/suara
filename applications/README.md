put all applications ON HERE

## Set Nginx
server {
	listen 80;
	server_name suara.dev;
	root APPLICATION_ROOT;
	index index.php index.html;
	location ~ .*\.(php)?$ {
		fastcgi_pass 127.0.0.1:9001;
		fastcgi_index index.php;
		include fastcgi.conf;
	}
	location ~ {
		if (!-e $request_filename) {
			rewrite ^/(.*)?$ /index.php last;
		}
	}
	location ~ .*.(gif|jpg|png|jpeg)$ {
		expires 60d;
	}
	location ~ .*.(js|css)$ {
		expires 30d;
	}
}


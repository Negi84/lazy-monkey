# lm-full-saas
Lazymonkey all codebase with mall saas

SSL Installation Steps:
1. Go to /etc/apache2/sites-available folder on server.
2. Edit 000-default.conf and 000-default-le-saal.conf file and copy virtual host content and paste (also edit server name in newly copied content) for both.
3. Restart the server: sudo service apache2 restart
4. open putty and run follwing commands.
	4.1) sudo add-apt-repository ppa:certbot/certbot
	4.2) sudo apt-get update (optional)
	4.3) sudo apt-get install python-certbot-apache
	4.4) sudo certbot --apache -d domain/subdomain

---All Done----	

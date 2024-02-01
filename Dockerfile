FROM gitpod/workspace-mysql

USER gitpod

RUN sudo apt update && sudo apt upgrade -y && sudo apt clean

#RUN sudo apt install -y apache2 
RUN sudo apt install -y ca-certificates
RUN sudo apt install -y apt-transport-https
RUN sudo apt install -y software-properties-common
RUN sudo apt install -y lsb-release

RUN sudo add-apt-repository ppa:ondrej/php -y
RUN sudo apt update && sudo apt upgrade -y && sudo apt clean

RUN sudo apt install -y php8.3 libapache2-mod-php8.3 php8.3-{cli,fpm,curl,mysqlnd,gd,opcache,zip,intl,common,bcmath,imagick,xmlrpc,readline,memcached,redis,mbstring,apcu,xml,dom,memcache,pcov,dev} && apt clean

COPY Docker/apache2.conf /etc/apache2/apache2.conf

ENTRYPOINT [ "sudo" "/usr/sbin/httpd" "-D" "FOREGROUND" ]

FROM gitpod/workspace-mysql

USER gitpod

COPY Docker/000-default.conf /etc/apache2/sites-available/000-default.conf

# RUN sudo apt update && sudo apt upgrade -y && sudo apt clean

#RUN sudo apt install -y apache2 
#RUN sudo apt install -y ca-certificates
#RUN sudo apt install -y apt-transport-https
#RUN sudo apt install -y software-properties-common
#RUN sudo apt install -y lsb-release

# RUN sudo add-apt-repository ppa:ondrej/php -y
# RUN sudo apt update && sudo apt upgrade -y && sudo apt clean

# RUN sudo apt install -y php8.3 libapache2-mod-php8.3 
# RUN sudo apt install -y php8.3-cli php8.3-fpm php8.3-curl php8.3-mysqlnd php8.3-gd php8.3-opcache php8.3-zip php8.3-intl php8.3-common php8.3-bcmath php8.3-mbstring php8.3-apcu php8.3-xml php8.3-dom php8.3-pcov php8.3-dev && apt clean

# ENTRYPOINT [ "sudo" "/usr/sbin/httpd" "-D" "FOREGROUND" ]

##AIFS v1.02 RC1

###Copyright (c) 2016, Digital Oversight




This framework implement large scale information gathering and analysis in order to provide valuable intelligence to a third party software. Using multiple source, this software calculate the risk factors related to a decision, regardeless of the initial objective and the resulting actions.

AIFS tries to implement general patterns related to machine learning and intelligence classification. The Core functionalities include libraries related to public content information, digital network information, geographical and human information.

Note that the code forming initial Github release of AIFS is a collection of tools and projects initially developed during 2006 and 2011 by the following developers : Vincent Menard, Julien Jouvent-Halle, Daniel Greenberg, Rabih Majzoub

##How to install


```
root@ns1:/var/www# git clone https://github.com/digitaloversight/aifs.git

root@ns1:/var/www/aifs/schema# mysql -u root -p < ./aifs/schema/aifs-base-1.02.sql

mysql> insert into user set Host='localhost', User='aifs', Password=PASSWORD('');

mysql> flush privileges;

mysql> grant all on aifs.*  to 'aifs'@'localhost';
```




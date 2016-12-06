# Zone (Media) Dynamic DNS (k^hhäkk)

[Zone Media](https://www.zone.ee)

[DataZone RESTful API](https://help.zone.eu/index.php?/Knowledgebase/Article/View/220/0/datazone-restful-api)

## Linux & sõbrad
```
wget --spider https://example.com/super-secret/zddns.php?secret1
```

## RouterOS
```
[admin@MikroTik] > system script add name=zddns policy=read,test source="/tool fetch url=\"https://example.com/super-secret/zddns.php\?secret1\" keep-result=no"
[admin@MikroTik] > system scheduler add name=zddns start-time=startup interval=45m policy=read,test on-event=zddns
```

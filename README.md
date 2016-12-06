# Zone (Media) Dynamic DNS (k^hhäkk)

[Zone Media](https://www.zone.ee)

[DataZone RESTful API](https://help.zone.eu/index.php?/Knowledgebase/Article/View/220/0/datazone-restful-api)

## Linux & sõbrad
```
wget --spider https://example.com/super-secret/zddns.php?secret1
```

## RouterOS
```
[admin@MikroTik] > system script print 
Flags: I - invalid 
 0   name="zddns" owner="admin" policy=read,test last-started=dec/06/2016 15:49:07 run-count=1684 
     source=/tool fetch url="https://example.com/super-secret/zddns.php?secret1" keep-result=no 
[admin@MikroTik] > system scheduler print 
Flags: X - disabled 
 #   NAME                              START-DATE  START-TIME                            INTERVAL             ON-EVENT                             RUN-COUNT
 0   zddns                                         startup                               45m                  zddns                                      222

```

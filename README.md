# Zone (Media) Dynamic DNS (k^hhäkk) v2

[Zone Media](https://www.zone.ee)

[ZoneID API](https://api.zone.eu/v2)

## Linux & sõbrad
```
wget --spider https://example.com/super-secret/zddns.php?secret1
```

## RouterOS
```
[admin@MikroTik] > system script add name=zddns policy=read,test source="/tool fetch url=\"https://example.com/super-secret/zddns.php\?secret1\" keep-result=no"
[admin@MikroTik] > system scheduler add name=zddns start-time=startup interval=45m policy=read,test on-event=zddns
```

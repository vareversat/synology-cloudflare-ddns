# Synology DynDNS using Cloudflare API

> [!NOTE]
> This script has been tested on a Synology DS218+ running DSM 7.1.1-42962 Update 6

This project is entended to add a DynDNS custom provider for Synology NAS

## The genesis 🧠

Synology NAS does not support Cloudflare DNS automatic update (I mean directly via Cloudflare API, it is possible to use DNS-o-matic to automaticly update you DNS record).
So I decided to create this little PHP script.

## How to

> [!IMPORTANT]
> You need to have a SSH access with root privileges to your NAS to perform the following commands

1. Add a new DynDNS providers
```bash
cat <<EOF >>/etc.defaults/ddns_provider.conf
[Cloudflare]
        modulepath=/usr/syno/bin/ddns/cloudflare.php
        queryurl=https://api.cloudflare.com/
EOF
```

2. Create a new PHP script file
```bash
vi /usr/syno/bin/ddns/cloudflare.php
```
And add the content of `cloudflare.php`

3. Set your Cloudflare zone ID
```bash
sed -i 's/__CLOUDFLARE_ZONE_ID__/YOUR_CLOUDFLARE_ZONE_ID/g' /usr/syno/bin/ddns/cloudflare.php
```

4. Setup your Synology DDNS parameters

Go to **Control Panel > Connectivity > External Access > DDNS** and **Add** a new entry

- *Service Provider* : Cloudflare
- *Hostname* : A valid domain name (ex: mynas.synology.com)
- *Username/Email* : The existing Cloudflare DNS record ID
- *Password/Key*: The Cloudflare API Token with **Zone DNS Edit** permission

Click on **Test Connection** and your NAS is ready to automaticly update your public domain name !



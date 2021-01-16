# Home Assistant Webhook NFC API

This little repository can be used to trigger Home Assistant automations without the need of an app.  
It simply translates the GET requests a phone will create if a NFC tag is read, to a POST request understood by the Home Assistant server.

**Keep in mind:**  
This allows everyone in your WIFI to trigger the automation without any authorization or app.  
I use this to toggle my lights, which I want every guest of my home to be able to do.

You shouldn't use this to trigger critical things, even if the NFC tag is hidden somewhere, because the webhook is open to everyone in your WIFI!  

## Installation

I'm running a webserver on my Raspberry PI, which also has Home Assistant installed.  
If you want to run the webserver on a different machine, you have to change the URL in the `ìndex.php`, which is set to `localhost`by default.

### Install Apache2 & PHP

* Install Apache2:  
  `sudo apt-get install apache2`
* Install PHP:  
  `sudo apt-get install php`
    
### Enable RewriteEngine

* Edit the Apache2 config:  
  `sudo nano /etc/apache2/apache2.conf`
* Find the directory settings:  
  `<Directory /var/www/>`
* Set:  
  `AllowOverride All`
    
### Change the Apache2 Port (Optional)

You can also change the port the server listens if you want to.

* Open the configuration file:
`sudo nano /etc/apache2/ports.conf`
* Change the port:
`Listen <choose-a-port>`  
  (I changed my port to `8124`)
    
### Clone the repository

* Navigate to the web directory:  
  `cd /var/www/html/<choose-a-project-name>/`  
  (You can also install it to `/var/www/html/` if this is the only usage of the webserver, as I did for the examples.)
* Clone the repository:  
  `sudo git clone https://github.com/Spomsoree/home-assistant-webhook-nfc-api.git .`
  
### Restart Apache2

* Restart Apache2, to load the new configuration:  
`sudo service apache2 restart`

### Test installation

Now we can test if the configuration works.

* Open the following URL in your browser:  
`http://<IP-of-your-pi>:<the-chosen-a-port>/<the-chosen-project-name>/api/webhook/something`  
  (For example: `http://192.168.0.2:8214/api/webhook/something`)

## Configuration

Now we are ready to set up the Home Assistant automations, and the NFC tags.

### NFC Tags

The NFC tags only need a web URL.  
I used this [NFC writer](https://play.google.com/store/apps/details?id=com.manjul.utility.nfc.writer&hl=de&gl=US) on my Android to write my tags.

* Write thw following URL on the NFC tag:  
`http://<IP-of-your-pi>:<the-chosen-a-port>/<the-chosen-project-name>/api/webhook/<choose-a-name>`  
  (For example: `http://192.168.0.2:8214/api/webhook/bathroom.light`)

### Automations

The last thing you need to do is set up a Home Assistant automation.  
You can either do this via the web frontend or directly edit you configuration file.

#### Web frontend

* Create a new automation in the web frontend of Home Assistant:
    * Set the trigger to `Webhook`:
        * Set the Webhook ID to your chosen webhook:  
        `<the-chosen-webhook>`  
          (For example: `bathroom.light`)
          
    * Set the action to do whatever you want to do.  
      (I've set that each webhook toggles a specific light.)
      
#### Home Assistant configuration

Alternatively you can create the automation in the configuration file:

```yaml
alias: <choose-a-title>
description: ''
trigger:
  - platform: webhook
    webhook_id: <the-chosen-webhook>
condition: []
action:
  - service: light.toggle
    data: {}
    entity_id: <your-light-id>
mode: single
```

Example:
```yaml
alias: If Webhook then Bathroom (TOGGLE)
description: ''
trigger:
  - platform: webhook
    webhook_id: bathroom.light
condition: []
action:
  - service: light.toggle
    data: {}
    entity_id: light.bathroom
mode: single
```

## Troubleshooting

I will try to support you if any errors occur during the setup.  
Just open a Github issue and add as much information as you can, and I'll try to solve it. 

## Improvements

If you have any ideas to improve this, I highly appreciate if you open an issue of create a pull request! 
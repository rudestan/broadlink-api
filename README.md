# Broadlink API PHP7 library 

A PHP 7 library for controlling IR and Wireless 433Mhz controllers from [Broadlink](http://www.ibroadlink.com/rm/). 
The protocol refer to: [mjg59/python-broadlink](https://github.com/mjg59/python-broadlink/blob/master/README.md)

Original code refer to: [ThePHPGuys/broadlink](https://github.com/ThePHPGuys/broadlink).

### What are the differences with original implementation from "ThePHPGuys"?

* Added full support of RM3 Pro Plus (learning mode, receiving and sending commands), so it became fully usable withing the api
* Code was refactored and logic a bit simplified
* Code was reformatted and cleaned to be able to easily understand the flow

### Usage

#### Discover devices

```php
use BroadlinkApi\Device\NetDevice; 

$discovered = (NetDevice::create())->discover();
```

The code will produce an array with Instances of corresponding Authenticatable (extended from ```AbstractAuthenticatableDevice::class```) devices or/and
with Instances of ```UnknownIndetifiedDevice::class``` in case no or some Unknown devices found.

#### Authorize device (get the cipher key)

To control previously discovered RM device it must be Authenticated. Let's say
the first device in the ```$discovered``` array is an instance of ```RMDevice``` class (or any other that extended
from ```AbstractAuthenticatableDevice::class```). The code will look like the following:

```php
use BroadlinkApi\Device\AuthenticatableDeviceInterface;
use BroadlinkApi\Exception\ProtocolException;

/** @var AuthenticatableDeviceInterface $device */
$device = $discovered[0];

try {
    $device->authenticate();
} catch(ProtocolException $e) {
    echo $e->getMessage();
}
```

#### Set device to learning mode

After RM device got Authenticated we can set it to learning mode for receiving commands from any remote control.
The following code will set the device to learning mode in case the device class is instace of RMDevice.

```php
use BroadlinkApi\Device\Authenticatable\RMDevice;

if ($device instanceof RMDevice) {
    try {
        $device->enterLearning();
    } catch(ProtocolException $e) {
        echo $e->getMessage();
    }
}
```

#### Receive last learned command from the Device

Once the RM Device is in learning mode we can receive the last learned command. We also need to wait
until the command arrives, so example code might look like this:

```php
$command = null;

while(true) {
    $command = $device->getLearnedCommand();       
    sleep(1);
    
    if ($command !== null) {
        break;
    }
}

var_dump($command->toArray());
```

An instance of ```Packet::class``` will be returned once the command will be received.

#### Send command to the Device

After receiving a Packet from ```RMDevice::getLearnedCommad()``` it can be converted to an array
by calling ```Packet::toArray()``` method. Converted array can easily be stored for example in json file
or in the DB and later can be reused to reproduce this commmand with the Device. To send previously learned
command the following code can be used:

```php
use BroadlinkApi\Exception\ProtocolException;

try {
    $device->sendIrCommand($command);
} catch (ProtocolException $e) {
    echo $e->getMessage();
} 
```

#### Other usage example

If the IP and Mac address of the device is already known (for example it was previously saved) as well
as the command (eg. loaded from JSON) the device can be easily used to trigger this command. Possible code
might look like the following:

```php
use BroadlinkApi\Device\Authenticatable\RMDevice;
use BroadlinkApi\Exception\ProtocolException;
use BroadlinkApi\Packet\Packet;

// Let's assume that $commandJson is json encoded array with received command 
$commandCode = json_decode($commandJson, true);
$command = Packet::fromArray($commandCode);

$rmDevice = new RMDevice('192.168.1.1', '77:0f:71:b9:5e:82');

try {
    $device->authenticate();
    $device->sendIrCommand($command);
} catch(ProtocolException $e) {
    echo $e->getMessage();
}
```

#### Use cases

The following API can be used for example to connect Google Home or Alexa together with Broadlink RM Pro for example 
through Raspberry PI as proxy hub. Or another way of usage can be creation of some web widget for Android instead of
using limited official Broadlink Application. This API extends the limits of the Device and it becomes
much more interesting.
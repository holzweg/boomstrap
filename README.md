BooMstrap - Bootstrap for Magento
=======

Why Bootstrap?
----------------------------------------------------------
We believe in a fast yet configurable setup with no pain on the front end side. So do you?

Kickstart your project with the awesome Bootstrap framework by Twitter! 

&rarr; See http://twitter.github.com/bootstrap for a full list of what's now at your fingertips.

Currently under heavy development!
======
A next major update from our side is planned May 2012, not earlier. If you whish to contribute in the meantime, we are happy to help!

How does it work?
----------------------------------------------------------
BooMstrap is a separate Magento theme, aimed at following all Magento standards. So, to get you started,

1. Install MageLess

        cd modules
        git clone git@github.com:holzweg/mageless.git 
This will add [LESS] (http://www.lesscss.org) support to your magento installation.
BooMstrap depends on this extension to render the bootstrap css through [LESS] (http://www.lesscss.org).  
@todo We're creating a "massetic" module instead...

2. Clone this repository into your modules directory

        cd modules
        git clone git@github.com:holzweg/boomstrap.git

3. Switch your store's (development) design to Boomstrap  
@todo Add further instructions

4. Optional: create symlinks for leaner access to the files  
@todo

5. Kick back, clear the cache and enjoy! (:

What is it exactly?
----------------------------------------------------------
BooMstrap overrides the default base layout to provide you with a [bootstrap] (http://twitter.github.com/bootstrap) enabled frontend.
We currently use version 2.0 and compile our CSS via Less.


### Configuration
Using the power of [LESS] (http://www.lesscss.org), you can overwrite many things such as colours, column widths and font sizes.  
&rarr; see modules/boomstrap/skin/frontend/boomstrap/default/less/*.less

You may want to override these files in your own extension to preserve the upgrade path.  

### Feedback and Feature Requests
We're happy to receive your feedback at technik[at]holzweg.com

Please feel to contribute by contacting us or simply sending us a pull request.

### Initially brought to you by Holzweg e-commerce Solutions, with love. ###
Note: No pixels were harmed during the development of this extension. It's Mage:ic()!

### Contributions by ###
Be the first one!

(function($) {
'use strict';
    jQuery(function() {
        var daftplugPublic = jQuery('.daftplugPublic');
        var optionName = daftplugPublic.attr('data-daftplug-plugin');
        var objectName = window[optionName + '_public_js_vars'];
        var client = new ClientJS();
        var isMobilePad = client.isMobile() || client.isIpad();
        var pushSubscribeButton = daftplugPublic.find('.daftplugPublicSubscribeButton');
        var navigationTabBar = daftplugPublic.find('.daftplugPublicNavigationTabBar');
        var isAndroidChrome = client.isMobileAndroid() && client.isChrome();
        var isAndroidFirefox = client.isMobileAndroid() && client.isFirefox();
        var isIosSafari = client.isMobileIOS() && client.isSafari();
        var isAndroidPwa = client.isMobileAndroid() && isPwa();
        var isIosPwa = client.isMobileIOS() && isPwa();
        var isFullscreenOverlayShown = getCookie('fullscreenOverlay');
        var isHeaderOverlayShown = getCookie('headerOverlay');
        var isMenuOverlayShown = getCookie('menuOverlay');
        var isCheckoutOverlayShown = getCookie('checkoutOverlay');
        var isPostOverlayShown = getCookie('postOverlay');
        var fullscreenOverlay = daftplugPublic.find('.daftplugPublicFullscreenOverlay');
        var chromeFullscreenOverlay = fullscreenOverlay.filter('.-chrome');
        var chrome2FullscreenOverlay = fullscreenOverlay.filter('.-chrome2');
        var firefoxFullscreenOverlay = fullscreenOverlay.filter('.-firefox');
        var safariFullscreenOverlay = fullscreenOverlay.filter('.-safari');
        var headerOverlay = daftplugPublic.find('.daftplugPublicHeaderOverlay');
        var menuOverlay = daftplugPublic.find('.daftplugPublicMenuOverlay');
        var checkoutOverlay = daftplugPublic.find('.daftplugPublicCheckoutOverlay');
        var postOverlay = daftplugPublic.find('.daftplugPublicPostOverlay');
        var installButton = daftplugPublic.find('.daftplugPublicInstallButton');

        // Check if PWA
        function isPwa() {
            return ['fullscreen', 'standalone', 'minimal-ui'].some(
                (displayMode) => window.matchMedia('(display-mode: '+displayMode+')').matches
            );
        }
        
        // Set cookie
        function setCookie(name, value, days) {
            var expires = '';
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = '; expires=' + date.toUTCString();
            }
            document.cookie = name + '=' + (value || '') + expires + '; path=/';
        }
        
        // Get cookie
        function getCookie(name) {
            var nameEQ = name + '=';
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        }

        // Change push button states
        function changePushButtonState(state) {
            switch (state) {
                case 'enabled':
                    pushSubscribeButton.removeClass('-loading').removeClass('-on').addClass('-off');
                    break;
                case 'disabled':
                    pushSubscribeButton.removeClass('-loading').removeClass('-off').addClass('-on');
                    break;
                case 'computing':
                    pushSubscribeButton.removeClass('-on').removeClass('-off').addClass('-loading');
                    break;
                case 'incompatible':
                    pushSubscribeButton.removeClass('-loading').removeClass('-off').addClass('-on').addClass('-disabled');
                    break;
                default:
                    console.error('Unhandled push button state', state);
                    break;
            }
        }

        // Base 64 to Unit8Array
        function urlBase64ToUint8Array(base64String) {
            const padding = '='.repeat((4 - (base64String.length % 4)) % 4);
            const base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');
            const rawData = window.atob(base64);
            const outputArray = new Uint8Array(rawData.length);
            for (let i = 0; i < rawData.length; ++i) {
                outputArray[i] = rawData.charCodeAt(i);
            }
            return outputArray;
        }

        // Check notification permission
        function checkNotificationPermission() {
            return new Promise((resolve, reject) => {
                if (Notification.permission === 'denied') {
                    return reject(new Error('Push messages are blocked.'));
                }
                if (Notification.permission === 'granted') {
                    return resolve();
                }
                if (Notification.permission === 'default') {
                    return Notification.requestPermission().then(result => {
                        if (result !== 'granted') {
                            reject(new Error('Bad permission result'));
                        }
                        resolve();
                    });
                }
            });
        }

        // Register push device
        function registerPushDevice() {
            changePushButtonState('computing');
            return checkNotificationPermission()
                .then(() => navigator.serviceWorker.ready)
                .then(serviceWorkerRegistration =>
                    serviceWorkerRegistration.pushManager.subscribe({
                        userVisibleOnly: true,
                        applicationServerKey: urlBase64ToUint8Array(objectName.pwaPublicKey),
                    })
                )
                .then(subscription => {
                    jQuery.toast({
                        title: 'Notifications are turned on',
                        duration: 2500,
                        position: 'bottom',
                    });
                    return handleSubscription(subscription, 'add');
                })
                .then(subscription => subscription && changePushButtonState('enabled'))
                .catch(e => {
                    if (Notification.permission === 'denied') {
                        console.warn('Notifications are denied by the user.');
                        changePushButtonState('incompatible');
                    } else {
                        console.error('Impossible to subscribe to push notifications', e);
                        changePushButtonState('disabled');
                    }
                });
        }

        // Deregister push device
        function deregisterPushDevice() {
            changePushButtonState('computing');
            navigator.serviceWorker.ready
                .then(serviceWorkerRegistration => serviceWorkerRegistration.pushManager.getSubscription())
                .then(subscription => {
                    if (!subscription) {
                        changePushButtonState('disabled');
                        return;
                    }
                    jQuery.toast({
                        title: 'Notifications are turned off',
                        duration: 2500,
                        position: 'bottom',
                    });
                    return handleSubscription(subscription, 'remove');
                })
                .then(subscription => subscription.unsubscribe())
                .then(() => changePushButtonState('disabled'))
                .catch(e => {
                    console.error('Error when unsubscribing the user', e);
                    changePushButtonState('disabled');
                });
        }

        // Handle subscription
        function handleSubscription(subscription, method) {
            const action = optionName + '_handle_subscription';
            const endpoint = subscription.endpoint;
            const userKey = subscription.getKey('p256dh');
            const userAuth = subscription.getKey('auth');
            const deviceInfo = client.getBrowser() + ' ' + client.getBrowserMajorVersion() + ' on ' + client.getOS() + ' ' + client.getOSVersion();
            const contentEncoding = (PushManager.supportedContentEncodings || ['aesgcm'])[0];

            return jQuery.ajax({
                url: objectName.ajaxUrl,
                type: 'POST',
                data: {
                    method: method,
                    action: action,
                    endpoint: endpoint,
                    userKey: userKey ? btoa(String.fromCharCode.apply(null, new Uint8Array(userKey))) : null,
                    userAuth: userAuth ? btoa(String.fromCharCode.apply(null, new Uint8Array(userAuth))) : null,
                    deviceInfo: deviceInfo,
                    contentEncoding,
                },
                beforeSend: function() {

                },
                success: function(response, textStatus, jqXhr) {

                },
                complete: function() {

                },
                error: function(jqXhr, textStatus, errorThrown) {

                }
            }).then(() => subscription);
        }

        // Handle push
        if ('serviceWorker' in navigator && 'PushManager' in window) {
            navigator.serviceWorker.ready.then(function(registration) {
                // Handle prompt on load
                if (objectName.settings.pwaPromptOnLoad == 'on') {
                    registration.pushManager.getSubscription().then(function(subscription) {
                        if (subscription) {
                            if (objectName.settings.pwaSubscribeButton == 'on') {
                                changePushButtonState('enabled');
                            }
                        } else {
                            registerPushDevice();
                        }
                    });
                }

                // Handle subscribe button
                if (objectName.settings.pwaSubscribeButton == 'on') {
                    pushSubscribeButton.css('display', 'flex');
                    registration.pushManager.getSubscription().then(function(subscription) {
                        if (subscription) {
                            changePushButtonState('enabled');
                        } else {
                            changePushButtonState('disabled');
                        }
                    });

                    daftplugPublic.on('click', '.daftplugPublicSubscribeButton', function(e) {
                        registration.pushManager.getSubscription().then(function(subscription) {
                            if (subscription) {
                                deregisterPushDevice();
                            } else {
                                registerPushDevice();
                            }
                        });
                    });
                }
            });
        }

        // Handle offline forms
        if (objectName.settings.pwaOfflineForms == 'on') {
            Array.from(document.querySelectorAll('form')).forEach(form => {
                new OfflineForm(form);
            })
        };

        // Handle ajaxify
        if (objectName.settings.pwaAjaxify == 'on') {
            if (objectName.settings.pwaAjaxifyForms == 'on') {
                var formsSelector = 'form:not(.no-ajaxy)';
            } else {
                var formsSelector = false;
            }

            jQuery().ajaxify({
                selector: 'a:not(.no-ajaxy)',
                forms: formsSelector,
                refresh: true,
                deltas : false,
                alwayshints: 'daftplug-instantify',
            });
        }

        // Handle preloader
        if (objectName.settings.pwaPreloader == 'on') {
            jQuery(window).on('beforeunload pronto.request', function(e) {
                e.returnValue = '';
                jQuery('.daftplugPublicPreloader').css('display', 'flex').hide().fadeIn(200);
            }).on('load pronto.render', function(e) {
                jQuery('.daftplugPublicPreloader').fadeOut('slow');
            });
        }

        // Handle mobile staff
        if (isMobilePad) {
            // Handle navigation tab bar
            if (objectName.settings.pwaNavigationTabBar == 'on') {
            	if (navigationTabBar.find('li').length == 0) {
            		navigationTabBar.hide();
            	}

                if (navigationTabBar.is(':visible')) {
                    jQuery('#daftplugPublicToastMessage').css('bottom', '80px');
                    if (objectName.settings.pwaSubscribeButton == 'on' && objectName.settings.pwaButtonPosition.indexOf('bottom') >= 0) {
                        pushSubscribeButton.css('bottom', '60px');
                    }
                }

                if (objectName.settings.pwaNavigationTabBarSearch == '*directSearch*') {
                    var searchItem = daftplugPublic.find('.daftplugPublicNavigationTabBar_item.-search');
                    var searchContainer = searchItem.find('.daftplugPublicNavigationTabBar_search');
                    var searchForm = searchContainer.find('.daftplugPublicNavigationTabBar_searchForm');
                    var searchField = searchForm.find('.daftplugPublicNavigationTabBar_searchField');

                    searchItem.click(function(e) {
                        searchContainer.fadeIn('fast', function(e) {
                            searchField.focus().blur(function(e) {
                                searchForm[0].reset();
                                searchContainer.fadeOut('fast');
                            });
                        });
                    });
                }
            }

            // Handle pull down navigation
            if (objectName.settings.pwaPullDownNavigation == 'on') {
                PullToNavigate();
                jQuery('#daftplugPublicPullDownNavigation').css('background', objectName.settings.pwaPullDownNavigationBgColor);
            }

            // Handle swipe navigation
            if (objectName.settings.pwaSwipeNavigation == 'on') {
                jQuery('body').attr('data-xthreshold', '111').swipeleft(function() { 
                    window.history.back();
                    jQuery.toast({
                        title: 'Moved Back',
                        duration: 3000,
                        position: 'bottom',
                    });
                }).swiperight(function() { 
                    window.history.forward(); 
                    jQuery.toast({
                        title: 'Moved Forward',
                        duration: 3000,
                        position: 'bottom',
                    });
                });
            }

            // Handle shake to refresh
            if (objectName.settings.pwaShakeToRefresh == 'on') {
                var shakeEvent = new Shake({threshold: 15});
                shakeEvent.start();
                window.addEventListener('shake', function() {
                    location.reload();
                }, false);
            }

            // Handle vibration
            if (objectName.settings.pwaVibration == 'on') {
                jQuery('body').vibrate();
            }

            // Handle installation overlays
            if (objectName.settings.pwaOverlays == 'on') {
                // Handle fullscreen installation overlays
                if (objectName.settings.pwaOverlaysTypes.includes('fullscreen') && isAndroidPwa == false && isIosPwa == false && isFullscreenOverlayShown == null && fullscreenOverlay.length) {
                    if (objectName.settings.pwaOverlaysBrowsers.includes('chrome') && isAndroidChrome && chromeFullscreenOverlay.length && chrome2FullscreenOverlay.length) {
                        navigator.serviceWorker.getRegistrations().then(registrations => {
                            if (!registrations.length == 0) {
                                var installPromptEvent = void 0;
                                var isFullscreenOverlayFired = false;
                                window.addEventListener('beforeinstallprompt', function(event) {
                                    event.preventDefault();
                                    installPromptEvent = event;
                                    if (!isFullscreenOverlayFired) {
                                        setTimeout(function() {
                                            chromeFullscreenOverlay.fadeIn('fast', function(e) {
                                            	isFullscreenOverlayFired = true;
                                                chromeFullscreenOverlay.on('click', '.daftplugPublicFullscreenOverlay_button', function(e) {
                                                	chromeFullscreenOverlay.fadeOut('fast', function(e) {
                                                		setCookie('fullscreenOverlay', 'shown', objectName.settings.pwaOverlaysShowAgain);
                                                    	installPromptEvent.prompt();
                                                    	installPromptEvent = null;
                                                    });
                                                });
                                            });
                                        }, 5000);
                                    }
                                });
                            } else {
                            	setTimeout(function() {
                                	chrome2FullscreenOverlay.fadeIn('fast');
                                }, 5000);
                            }
                        });
                    } else if (objectName.settings.pwaOverlaysBrowsers.includes('firefox') && isAndroidFirefox && firefoxFullscreenOverlay.length) {
                        setTimeout(function() {
                            firefoxFullscreenOverlay.fadeIn('fast');
                        }, 5000);
                    } else if (objectName.settings.pwaOverlaysBrowsers.includes('safari') && isIosSafari && safariFullscreenOverlay.length) {
                        setTimeout(function() {
                            safariFullscreenOverlay.fadeIn('fast');
                        }, 5000);
                    }

                    fullscreenOverlay.on('click', '.daftplugPublicFullscreenOverlay_close', function(e) {
						fullscreenOverlay.fadeOut('fast', function(e) {
	                    	setCookie('fullscreenOverlay', 'shown', objectName.settings.pwaOverlaysShowAgain);
						});
					});
                }

                // Handle header installation overlay
                if (objectName.settings.pwaOverlaysTypes.includes('header') && isAndroidPwa == false && isIosPwa == false && isHeaderOverlayShown == null && headerOverlay.length) {
                    if (objectName.settings.pwaOverlaysBrowsers.includes('chrome') && isAndroidChrome && chrome2FullscreenOverlay.length) {
                        navigator.serviceWorker.getRegistrations().then(registrations => {
                            if (!registrations.length == 0) {
                                var installPromptEvent = void 0;
                                var isHeaderOverlayFired = false;
                                window.addEventListener('beforeinstallprompt', function(event) {
                                    event.preventDefault();
                                    installPromptEvent = event;
                                    if (!isHeaderOverlayFired) {
                                        setTimeout(function() {
                                            headerOverlay.css('display', 'flex').hide().fadeIn('fast', function(e) {
                                            	isHeaderOverlayFired = true;
                                            	headerOverlay.on('click', '.daftplugPublicHeaderOverlay_button', function(e) {
	                                            	headerOverlay.fadeOut('fast', function(e) {
	                                                    setCookie('headerOverlay', 'shown', objectName.settings.pwaOverlaysShowAgain);
	                                                    installPromptEvent.prompt();
	                                                    installPromptEvent = null;
	                                                });
	                                            });
                                            });
                                        }, 5000);
                                    }
                                });
                            } else {
                                setTimeout(function() {
                                    headerOverlay.css('display', 'flex').hide().fadeIn('fast').on('click', '.daftplugPublicHeaderOverlay_button', function(e) {
                                        headerOverlay.fadeOut('fast', function(e) {
                                            setCookie('headerOverlay', 'shown', objectName.settings.pwaOverlaysShowAgain);
                                            chrome2FullscreenOverlay.fadeIn('fast').on('click', '.daftplugPublicFullscreenOverlay_close', function(e) {
                                                chrome2FullscreenOverlay.fadeOut('fast');
                                            });
                                        });
                                    });
                                }, 5000);
                            }
                        });
                    } else if (objectName.settings.pwaOverlaysBrowsers.includes('firefox') && isAndroidFirefox && firefoxFullscreenOverlay.length) {
                        setTimeout(function() {
                            headerOverlay.css('display', 'flex').hide().fadeIn('fast').on('click', '.daftplugPublicHeaderOverlay_button', function(e) {
                                headerOverlay.fadeOut('fast', function(e) {
                                    setCookie('headerOverlay', 'shown', objectName.settings.pwaOverlaysShowAgain);
                                    firefoxFullscreenOverlay.fadeIn('fast').on('click', '.daftplugPublicFullscreenOverlay_close', function(e) {
                                        firefoxFullscreenOverlay.fadeOut('fast');
                                    });
                                });
                            });
                        }, 5000);
                    } else if (objectName.settings.pwaOverlaysBrowsers.includes('safari') && isIosSafari && safariFullscreenOverlay.length) {
                        setTimeout(function() {
                            headerOverlay.css('display', 'flex').hide().fadeIn('fast').on('click', '.daftplugPublicHeaderOverlay_button', function(e) {
                                headerOverlay.fadeOut('fast', function(e) {
                                    setCookie('headerOverlay', 'shown', objectName.settings.pwaOverlaysShowAgain);
                                    safariFullscreenOverlay.fadeIn('fast').on('click', '.daftplugPublicFullscreenOverlay_close', function(e) {
                                        safariFullscreenOverlay.fadeOut('fast');
                                    });
                                });
                            });
                        }, 5000);
                    }

                    headerOverlay.on('click', '.daftplugPublicHeaderOverlay_dismiss', function(e) {
                        headerOverlay.fadeOut('fast', function(e) {
                            setCookie('headerOverlay', 'shown', objectName.settings.pwaOverlaysShowAgain);
                        });
                    });
                }

                // Handle menu installation overlay
                if (objectName.settings.pwaOverlaysTypes.includes('menu') && isAndroidPwa == false && isIosPwa == false && isMenuOverlayShown == null && menuOverlay.length) {
                    if (objectName.settings.pwaOverlaysBrowsers.includes('chrome') && isAndroidChrome && chrome2FullscreenOverlay.length) {
                        navigator.serviceWorker.getRegistrations().then(registrations => {
                            if (!registrations.length == 0) {
                                var installPromptEvent = void 0;
                                var isMenuOverlayFired = false;
                                window.addEventListener('beforeinstallprompt', function(event) {
                                    event.preventDefault();
                                    installPromptEvent = event;
                                    if (!isMenuOverlayFired) {
                                        menuOverlay.css('display', 'flex').hide().fadeIn('fast', function(e) {
                                        	isMenuOverlayFired = true;
                                        	menuOverlay.on('click', '.daftplugPublicMenuOverlay_install', function(e) {
	                                            menuOverlay.fadeOut('fast', function(e) {
	                                                setCookie('menuOverlay', 'shown', objectName.settings.pwaOverlaysShowAgain);
	                                                installPromptEvent.prompt();
	                                                installPromptEvent = null;
	                                            });
	                                        });
                                        });
                                    }
                                });
                            } else {
                                menuOverlay.css('display', 'flex').hide().fadeIn('fast').on('click', '.daftplugPublicMenuOverlay_install', function(e) {
                                    menuOverlay.fadeOut('fast', function(e) {
                                        setCookie('menuOverlay', 'shown', objectName.settings.pwaOverlaysShowAgain);
                                        chrome2FullscreenOverlay.fadeIn('fast').on('click', '.daftplugPublicFullscreenOverlay_close', function(e) {
                                            chrome2FullscreenOverlay.fadeOut('fast');
                                        });
                                    });
                                });
                            }
                        });
                    } else if (objectName.settings.pwaOverlaysBrowsers.includes('firefox') && isAndroidFirefox && firefoxFullscreenOverlay.length) {
                        menuOverlay.css('display', 'flex').hide().fadeIn('fast').on('click', '.daftplugPublicMenuOverlay_install', function(e) {
                            menuOverlay.fadeOut('fast', function(e) {
                                setCookie('menuOverlay', 'shown', objectName.settings.pwaOverlaysShowAgain);
                                firefoxFullscreenOverlay.fadeIn('fast').on('click', '.daftplugPublicFullscreenOverlay_close', function(e) {
                                    firefoxFullscreenOverlay.fadeOut('fast');
                                });
                            });
                        });
                    } else if (objectName.settings.pwaOverlaysBrowsers.includes('safari') && isIosSafari && safariFullscreenOverlay.length) {
                        menuOverlay.css('display', 'flex').hide().fadeIn('fast').on('click', '.daftplugPublicMenuOverlay_install', function(e) {
                            menuOverlay.fadeOut('fast', function(e) {
                                setCookie('menuOverlay', 'shown', objectName.settings.pwaOverlaysShowAgain);
                                safariFullscreenOverlay.fadeIn('fast').on('click', '.daftplugPublicFullscreenOverlay_close', function(e) {
                                    safariFullscreenOverlay.fadeOut('fast');
                                });
                            });
                        });
                    }

                    menuOverlay.on('click', '.daftplugPublicMenuOverlay_dismiss', function(e) {
                        menuOverlay.fadeOut('fast', function(e) {
                            setCookie('menuOverlay', 'shown', objectName.settings.pwaOverlaysShowAgain);
                        });
                    });
                }

                // Handle checkout installation overlay
                if (objectName.settings.pwaOverlaysTypes.includes('checkout') && isAndroidPwa == false && isIosPwa == false && isCheckoutOverlayShown == null && checkoutOverlay.length) {
                    if (objectName.settings.pwaOverlaysBrowsers.includes('chrome') && isAndroidChrome && chrome2FullscreenOverlay.length) {
                        navigator.serviceWorker.getRegistrations().then(registrations => {
                            if (!registrations.length == 0) {
                                var installPromptEvent = void 0;
                                var isCheckoutOverlayFired = false;
                                window.addEventListener('beforeinstallprompt', function(event) {
                                    event.preventDefault();
                                    installPromptEvent = event;
                                    if (!isCheckoutOverlayFired) {
                                        checkoutOverlay.css('display', 'flex').hide().fadeIn('fast', function(e) {
                                        	isCheckoutOverlayFired = true;
                                        	checkoutOverlay.on('click', '.daftplugPublicCheckoutOverlay_install', function(e) {
	                                            checkoutOverlay.fadeOut('fast', function(e) {
	                                                setCookie('checkoutOverlay', 'shown', objectName.settings.pwaOverlaysShowAgain);
	                                                installPromptEvent.prompt();
	                                                installPromptEvent = null;
	                                            });
                                            });
                                        });
                                    }
                                });
                            } else {
                                checkoutOverlay.css('display', 'flex').hide().fadeIn('fast').on('click', '.daftplugPublicCheckoutOverlay_install', function(e) {
                                    checkoutOverlay.fadeOut('fast', function(e) {
                                        setCookie('checkoutOverlay', 'shown', objectName.settings.pwaOverlaysShowAgain);
                                        chrome2FullscreenOverlay.fadeIn('fast').on('click', '.daftplugPublicFullscreenOverlay_close', function(e) {
                                            chrome2FullscreenOverlay.fadeOut('fast');
                                        });
                                    });
                                });
                            }
                        });
                    } else if (objectName.settings.pwaOverlaysBrowsers.includes('firefox') && isAndroidFirefox && firefoxFullscreenOverlay.length) {
                        checkoutOverlay.css('display', 'flex').hide().fadeIn('fast').on('click', '.daftplugPublicCheckoutOverlay_install', function(e) {
                            checkoutOverlay.fadeOut('fast', function(e) {
                                setCookie('checkoutOverlay', 'shown', objectName.settings.pwaOverlaysShowAgain);
                                firefoxFullscreenOverlay.fadeIn('fast').on('click', '.daftplugPublicFullscreenOverlay_close', function(e) {
                                    firefoxFullscreenOverlay.fadeOut('fast');
                                });
                            });
                        });
                    } else if (objectName.settings.pwaOverlaysBrowsers.includes('safari') && isIosSafari && safariFullscreenOverlay.length) {
                        checkoutOverlay.css('display', 'flex').hide().fadeIn('fast').on('click', '.daftplugPublicCheckoutOverlay_install', function(e) {
                            checkoutOverlay.fadeOut('fast', function(e) {
                                setCookie('checkoutOverlay', 'shown', objectName.settings.pwaOverlaysShowAgain);
                                safariFullscreenOverlay.fadeIn('fast').on('click', '.daftplugPublicFullscreenOverlay_close', function(e) {
                                    safariFullscreenOverlay.fadeOut('fast');
                                });
                            });
                        });
                    }

                    checkoutOverlay.on('click', '.daftplugPublicCheckoutOverlay_dismiss', function(e) {
                        checkoutOverlay.fadeOut('fast', function(e) {
                            setCookie('checkoutOverlay', 'shown', objectName.settings.pwaOverlaysShowAgain);
                        });
                    });
                }

                // Handle post installation overlay
                if (objectName.settings.pwaOverlaysTypes.includes('post') && isAndroidPwa == false && isIosPwa == false && isPostOverlayShown == null && postOverlay.length) {
                    if (objectName.settings.pwaOverlaysBrowsers.includes('chrome') && isAndroidChrome && chrome2FullscreenOverlay.length) {
                        navigator.serviceWorker.getRegistrations().then(registrations => {
                            if (!registrations.length == 0) {
                                var installPromptEvent = void 0;
                                var isPostOverlayFired = false;
                                window.addEventListener('beforeinstallprompt', function(event) {
                                    event.preventDefault();
                                    installPromptEvent = event;
                                    if (!isPostOverlayFired) {
                                        setTimeout(function() {
                                            postOverlay.fadeIn('fast', function(e) {
                                            	isPostOverlayFired = true;
                                            	postOverlay.on('click', '.daftplugPublicPostOverlayAction_button.-install', function(e) {
	                                                postOverlay.fadeOut('fast', function(e) {
	                                                    setCookie('postOverlay', 'shown', objectName.settings.pwaOverlaysShowAgain);
	                                                    installPromptEvent.prompt();
	                                                    installPromptEvent = null;
	                                                });
                                                });
                                            });
                                        }, 5000);
                                    }
                                });
                            } else {
                                setTimeout(function() {
                                    postOverlay.fadeIn('fast').on('click', '.daftplugPublicPostOverlayAction_button.-install', function(e) {
                                        postOverlay.fadeOut('fast', function(e) {
                                            setCookie('postOverlay', 'shown', objectName.settings.pwaOverlaysShowAgain);
                                            chrome2FullscreenOverlay.fadeIn('fast').on('click', '.daftplugPublicFullscreenOverlay_close', function(e) {
                                                chrome2FullscreenOverlay.fadeOut('fast');
                                            });
                                        });
                                    });
                                }, 5000);
                            }
                        });
                    } else if (objectName.settings.pwaOverlaysBrowsers.includes('firefox') && isAndroidFirefox && firefoxFullscreenOverlay.length) {
                        setTimeout(function() {
                            postOverlay.fadeIn('fast').on('click', '.daftplugPublicPostOverlayAction_button.-install', function(e) {
                                postOverlay.fadeOut('fast', function(e) {
                                    setCookie('postOverlay', 'shown', objectName.settings.pwaOverlaysShowAgain);
                                    firefoxFullscreenOverlay.fadeIn('fast').on('click', '.daftplugPublicFullscreenOverlay_close', function(e) {
                                        firefoxFullscreenOverlay.fadeOut('fast');
                                    });
                                });
                            });
                        }, 5000);
                    } else if (objectName.settings.pwaOverlaysBrowsers.includes('safari') && isIosSafari && safariFullscreenOverlay.length) {
                        setTimeout(function() {
                            postOverlay.fadeIn('fast').on('click', '.daftplugPublicPostOverlayAction_button.-install', function(e) {
                                postOverlay.fadeOut('fast', function(e) {
                                    setCookie('postOverlay', 'shown', objectName.settings.pwaOverlaysShowAgain);
                                    safariFullscreenOverlay.fadeIn('fast').on('click', '.daftplugPublicFullscreenOverlay_close', function(e) {
                                        safariFullscreenOverlay.fadeOut('fast');
                                    });
                                });
                            });
                        }, 5000);
                    }

                    postOverlay.on('click', '.daftplugPublicPostOverlayAction_button.-dismiss', function(e) {
                        postOverlay.fadeOut('fast', function(e) {
                            setCookie('postOverlay', 'shown', objectName.settings.pwaOverlaysShowAgain);
                        });
                    });
                }

                setTimeout(function() {
                	var pushDisplayValue = pushSubscribeButton.css('display');
                    var navDisplayValue = navigationTabBar.css('display');
	                setInterval(function() {
	                    if (fullscreenOverlay.is(':visible') || postOverlay.is(':visible')) {
	                    	if (pushDisplayValue == 'flex') {
	                    		pushSubscribeButton.fadeOut('fast');
	                    	}
                            if (navDisplayValue == 'flex') {
                                navigationTabBar.fadeOut('fast');
                            }
	                    } else {
	                    	pushSubscribeButton.css('display', pushDisplayValue);
                            navigationTabBar.css('display', navDisplayValue);
	                    }
	                }, 100);
                }, 3000);
            }

            // Handle installation button
            if (objectName.settings.pwaInstallButton == 'on') {
                if (isAndroidPwa == false && isIosPwa == false && installButton.length) {
                    if (objectName.settings.pwaInstallButtonBrowsers.includes('chrome') && isAndroidChrome && chrome2FullscreenOverlay.length) {
                        navigator.serviceWorker.getRegistrations().then(registrations => {
                            if (!registrations.length == 0) {
                                var installPromptEvent = void 0;
                                window.addEventListener('beforeinstallprompt', function(event) {
                                    event.preventDefault();
                                    installPromptEvent = event;
                                    installButton.css('display', 'inline-block').on('click', function(e) {
                                        installPromptEvent.prompt();
                                        installPromptEvent = null;
                                    });
                                });
                            } else {
                                installButton.css('display', 'inline-block').on('click', function(e) {
                                    chrome2FullscreenOverlay.fadeIn('fast').on('click', '.daftplugPublicFullscreenOverlay_close', function(e) {
                                        chrome2FullscreenOverlay.fadeOut('fast');
                                    });
                                });
                            }
                        });
                    } else if (objectName.settings.pwaInstallButtonBrowsers.includes('firefox') && isAndroidFirefox && firefoxFullscreenOverlay.length) {
                        installButton.css('display', 'inline-block').on('click', function(e) {
                            firefoxFullscreenOverlay.fadeIn('fast').on('click', '.daftplugPublicFullscreenOverlay_close', function(e) {
                                firefoxFullscreenOverlay.fadeOut('fast');
                            });
                        });
                    } else if (objectName.settings.pwaInstallButtonBrowsers.includes('safari') && isIosSafari && safariFullscreenOverlay.length) {
                        installButton.css('display', 'inline-block').on('click', function(e) {
                            safariFullscreenOverlay.fadeIn('fast').on('click', '.daftplugPublicFullscreenOverlay_close', function(e) {
                                safariFullscreenOverlay.fadeOut('fast');
                            });
                        });
                    }
                }
            }

            // Handle iOS pwa stuff
            if (isIosPwa) {
                //Stop link clicks out of the iOS pwa
                var noddy, remotes = false;
                document.addEventListener('click', function(event) {
                    noddy = event.target;
                    if (noddy.tagName.toLowerCase() !== 'a' || noddy.hostname !== window.location.hostname || noddy.pathname !== window.location.pathname || !/#/.test(noddy.href)) return;

                    while (noddy.nodeName !== 'A' && noddy.nodeName !== 'HTML') {
                        noddy = noddy.parentNode;
                    }

                    if ('href' in noddy && noddy.href.indexOf('http') !== -1 && (noddy.href.indexOf(document.location.host) !== -1 || remotes)) {
                        event.preventDefault();
                        document.location.href = noddy.href;
                    }
                }, false);

                // Display rotate device notice based on orientation
                setInterval(function() {
                    if ((objectName.settings.pwaOrientation == 'portrait' && window.matchMedia('(orientation: landscape)').matches) || (objectName.settings.pwaOrientation == 'landscape' && window.matchMedia('(orientation: portrait)').matches)) {
                        daftplugPublic.find('.daftplugPublicRotateNotice').css('display', 'flex');
                        window.onorientationchange = function(e) {
                            daftplugPublic.find('.daftplugPublicRotateNotice').hide();
                        };
                    }
                }, 100);
            }

            // Handle both pwa stuff
            if (isPwa()) {
                if (jQuery('form').length) {
                    jQuery('form').attr('data-persist', 'garlic');
                }
            }
        }

        // Handle PWA installation analytics
        window.onappinstalled = function(e) { 
            jQuery.ajax({
                url: objectName.ajaxUrl,
                dataType: 'text',
                type: 'POST',
                data: {
                    action: optionName + '_save_installation_analytics',
                },
                beforeSend: function() {
                    console.log('saving');
                },
                success: function(response, textStatus, jqXhr) {
                    console.log('saved');
                },
                complete: function() {

                },
                error: function(jqXhr, textStatus, errorThrown) {
                    console.log(jqXhr);
                }
            }); 
        };
    });
})(jQuery);
(function($) {
'use strict';
    jQuery(function() {
        var daftplugAdmin = jQuery('.daftplugAdmin');
        var optionName = daftplugAdmin.attr('data-daftplug-plugin');
        var objectName = window[optionName + '_admin_js_vars'];

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

        // Handle URLs
        if (daftplugAdmin.find('.daftplugAdminPage.-activation').length) {
            window.location.hash = '#/activation/';
            daftplugAdmin.find('.daftplugAdminPage.-activation').addClass('-active');
            daftplugAdmin.find('.daftplugAdminHeader').css('justify-content', 'center');
            daftplugAdmin.find('.daftplugAdminHeader_versionText, .daftplugAdminHeader_support').hide();
        } else {
            if (window.location.hash) {
                var hash = window.location.hash.replace(/#|\//g, '').split('-');
                var pageId = hash[0];
                var subPageId = hash[1];
                var page = daftplugAdmin.find('.daftplugAdminPage.-' + pageId);
                var menuItem = daftplugAdmin.find('.daftplugAdminMenu_item.-' + pageId);
                var subPage = daftplugAdmin.find('.daftplugAdminPage_subpage.-' + subPageId);
                var subMenuItem = daftplugAdmin.find('.daftplugAdminSubmenu_item.-' + subPageId);
                var hasSubPages = page.find('.daftplugAdminPage_subpage').length;
                var firstSubPage = page.find('.daftplugAdminPage_subpage').first();
                var firstSubPageId = firstSubPage.attr('data-subpage');
                var firstSubMenuItem = page.find('.daftplugAdminSubmenu_item').first();
                var errorPage = daftplugAdmin.find('.daftplugAdminPage.-error');

                if (page.length) {
                    page.addClass('-active');
                    menuItem.addClass('-active');

                    if (hasSubPages) {
                        if (hash.includes(subPageId)) {
                            if (subPage.length) {
                                subPage.addClass('-active');
                                subMenuItem.addClass('-active');
                            } else {
                                page.removeClass('-active');
                                menuItem.removeClass('-active');
                                errorPage.addClass('-active');
                            }
                        } else {
                            firstSubPage.addClass('-active');
                            firstSubMenuItem.addClass('-active');
                            window.location.hash = '#/'+pageId+'-'+firstSubPageId+'/';
                        }
                    }
                } else {
                    errorPage.addClass('-active');
                }
            } else {
                window.location.hash = '#/overview/';
                daftplugAdmin.find('.daftplugAdminPage.-overview').addClass('-active');
                daftplugAdmin.find('.daftplugAdminMenu_item.-overview').addClass('-active');
            }
        }

        // Handle navigation
        daftplugAdmin.on('click', 'a[data-page]', function(e) {
            var self = jQuery(this);
            var pageId = self.attr('data-page');
            var page = daftplugAdmin.find('.daftplugAdminPage.-' + pageId);
            var menuItem = daftplugAdmin.find('.daftplugAdminMenu_item.-' + pageId);
            var subPage = page.find('.daftplugAdminPage_subpage');
            var hasSubPages = subPage.length;
            var firstSubPage = subPage.first();
            var firstSubPageId = firstSubPage.attr('data-subpage');
            var subMenuItem = page.find('.daftplugAdminSubmenu_item');
            var firstSubMenuItem = subMenuItem.first();

            daftplugAdmin.find('.daftplugAdminPage').removeClass('-active');
            page.addClass('-active');

            daftplugAdmin.find('.daftplugAdminMenu_item').removeClass('-active');
            menuItem.addClass('-active');

            if (hasSubPages) {
                subPage.add(subMenuItem).removeClass('-active');
                firstSubPage.add(firstSubMenuItem).addClass('-active');
            } 
        });

        // Handle subnavigation
        daftplugAdmin.on('click', 'a[data-subpage]', function(e) {
            var self = jQuery(this);
            var subPageId = self.attr('data-subpage');
            var subPage = daftplugAdmin.find('.daftplugAdminPage_subpage.-' + subPageId);
            var subMenuItem = daftplugAdmin.find('.daftplugAdminSubmenu_item.-' + subPageId);

            daftplugAdmin.find('.daftplugAdminPage_subpage').removeClass('-active');
            subPage.addClass('-active');

            daftplugAdmin.find('.daftplugAdminSubmenu_item').removeClass('-active');
            subMenuItem.addClass('-active');
        });

        // Handle FAQ
        daftplugAdmin.find('.daftplugAdminFaq_item').each(function(e) {
            var self = jQuery(this);
            var question = self.find('.daftplugAdminFaq_question');

            question.click(function(e) {
                if (self.hasClass('-active')) {
                    self.removeClass('-active');
                } else {
                    daftplugAdmin.find('.daftplugAdminFaq_item').removeClass('-active');
                    self.addClass('-active');
                }
            });
        });

        // Handle submit button
        daftplugAdmin.find('.daftplugAdminButton.-submit').each(function(e) {
            var self = jQuery(this);
            var submitText = self.attr('data-submit');
            var waitingText = self.attr('data-waiting');
            var submittedText = self.attr('data-submitted');
            var failedText = self.attr('data-failed');

            self.html(`<span class="daftplugAdminButton_iconset">
                           <svg class="daftplugAdminButton_icon -iconSubmit">
                               <use href="#iconSubmit"></use>
                           </svg>
                           <svg class="daftplugAdminButton_icon -iconLoading">
                               <use href="#iconLoading"></use>
                           </svg>
                           <svg class="daftplugAdminButton_icon -iconSuccess">
                               <use href="#iconSuccess"></use>
                           </svg>
                           <svg class="daftplugAdminButton_icon -iconFail">
                               <use href="#iconFail"></use>
                           </svg>
                       </span>
                       <ul class="daftplugAdminButton_textset">
                           <li class="daftplugAdminButton_text -submit">
                               ${submitText}
                           </li>
                           <li class="daftplugAdminButton_text -waiting">
                               ${waitingText}
                           </li>
                           <li class="daftplugAdminButton_text -submitted">
                               ${submittedText}
                           </li>
                           <li class="daftplugAdminButton_text -submitFailed">
                               ${failedText}
                           </li>
                       </ul>`);

            var buttonTexts = self.find('.daftplugAdminButton_textset');
            var buttonText = buttonTexts.find('.daftplugAdminButton_text');
            var buttonIcons = self.find('.daftplugAdminButton_iconset');
            var buttonIcon = self.find('.daftplugAdminButton_icon');
            var longestButtonTextChars = '';

            buttonText.each(function(e) {
                var self = jQuery(this);
				var buttonTextChars = self.text();
				if (buttonTextChars.length > longestButtonTextChars.length) {
					longestButtonTextChars = buttonTextChars;
				}
            });

            buttonTexts.css('width', jQuery.trim(longestButtonTextChars).length * 7.5 +'px');

            if (self.hasClass('-confirm')) {
                var sureText = self.attr('data-sure');
                var confirmDuration = self.attr('data-duration');
                var clickDuration = 0;

                self.attr('style', `--confirmDuration:${confirmDuration};`);
                self.on('mousedown touchstart', function(e) {
                    e.preventDefault();
                    buttonText.filter('.-waiting').text(sureText);
                    self.addClass('-loading -progress');
                    clickDuration = setTimeout(function(e) {
                        buttonText.filter('.-waiting').text(waitingText);
                        self.removeClass('-loading -progress').trigger('submit');
                    }, parseInt(confirmDuration));
                }).on('mouseup touchend', function(e) {
                    self.removeClass('-loading -progress');
                    clearTimeout(clickDuration);
                });
            }
        });

        // Handle add field button
        daftplugAdmin.find('.daftplugAdminButton.-addField').each(function(e) {
            var self = jQuery(this);
            var addTaget = self.attr('data-add');
            var max = parseInt(self.attr('data-max'));
            var miniFieldset = daftplugAdmin.find('.-miniFieldset[class*="-'+addTaget+'"]');
            var i = 0;

            miniFieldset.each(function(e) {
                var self = jQuery(this);
                var miniFieldsetCheckbox = self.find('.daftplugAdminInputCheckbox.-hidden');
                var miniFieldsetCheckboxField = miniFieldsetCheckbox.find('.daftplugAdminInputCheckbox_field');
                self.find('.daftplugAdminField').addClass('-'+miniFieldsetCheckboxField.attr('id')+'DependentHideD');
                if (miniFieldsetCheckboxField.is(':checked')) {
                    self.show();
                    i++;
                } else {
                    self.hide();
                }
            });

            miniFieldset.prepend(`
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="daftplugAdminMinifielset_close -iconClose">
                    <g stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="10" cy="10" r="10" id="circle"></circle>
                        <path d="M7,7 L13,13" id="line"></path>
                        <path d="M7,13 L13,7" id="line"></path>
                    </g>
                </svg>
            `);

            var close = miniFieldset.find('.daftplugAdminMinifielset_close');

            self.click(function(e) {  
                i++;
                miniFieldset.filter('.-miniFieldset[class*="-'+addTaget+i+'"]').show();
                miniFieldset.find('.daftplugAdminInputCheckbox_field[id="'+addTaget+i+'"]').prop('checked', true).change();
                if (i == max) {
                    self.hide();
                }
            });

            close.click(function(e) {
                self.show();
                miniFieldset.filter('.-miniFieldset[class*="-' + addTaget+i+'"]').hide();
                miniFieldset.find('.daftplugAdminInputCheckbox_field[id="'+addTaget+i+'"]').prop('checked', false).change();
                if (i != 0) {
                    i--;
                }
            });
        });

        // Handle tooltips
        daftplugAdmin.on('mouseenter mouseleave', '[data-tooltip]', function(e) {
            var self = jQuery(this);
            var tooltip = self.attr('data-tooltip');

            if (e.type === 'mouseenter') {
                self.append(`<span class="daftplugAdminTooltip">${tooltip}</span>`);
            }

            if (e.type === 'mouseleave') {
                self.find('.daftplugAdminTooltip').remove();
            }
        });

        // Handle loader
        daftplugAdmin.find('.daftplugAdminLoader').each(function(e) {
            var self = jQuery(this);
            var size = self.attr('data-size');
            var duration = self.attr('data-duration');

            self.html(`
                <div class="daftplugAdminLoader_box">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
                <div class="daftplugAdminLoader_box">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
                <div class="daftplugAdminLoader_box">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
                <div class="daftplugAdminLoader_box">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            `).attr('style', `--size:${size};--duration:${duration}`);
        });

        // Handle popup
        daftplugAdmin.find('.daftplugAdminPopup').each(function(e) {
            var self = jQuery(this);
            var openPopup = self.attr('data-popup');
            var popupContainer = self.find('.daftplugAdminPopup_container');

            daftplugAdmin.find('[data-open-popup="'+openPopup+'"]').on('click', function(e) {
                self.addClass('-active');
            });

            popupContainer.on('click', function(e) {
                e.stopPropagation();
            }).find('fieldset').css('border', 'none');

            self.on('click', function(e) {
                self.removeClass('-active');
            });
        });

        // Handle input has value
        daftplugAdmin.find('.daftplugAdminInputText, .daftplugAdminInputNumber, .daftplugAdminInputTextarea, .daftplugAdminInputColor').each(function(e) {
            var self = jQuery(this);
            var field = self.find('.daftplugAdminInputText_field, .daftplugAdminInputNumber_field, .daftplugAdminInputTextarea_field, .daftplugAdminInputColor_field');

            field.on('change keyup paste', function() {
                field.val().length ? field.addClass('-hasValue') : field.removeClass('-hasValue');
            }).change();
        });

        // Handle text input
        daftplugAdmin.find('.daftplugAdminInputText').each(function(e) {
            var self = jQuery(this);
            var field = self.find('.daftplugAdminInputText_field');
            var placeholder = field.attr('data-placeholder');

            field.after('<span class="daftplugAdminInputText_placeholder">' + placeholder + '</span>');

            field.on('invalid', function(e) {
                self.addClass('-invalid');
                setTimeout(function(e) {
                    self.removeClass('-invalid');
                }, 2300);
            });
        });

        // Handle textarea
        daftplugAdmin.find('.daftplugAdminInputTextarea').each(function(e) {
            var self = jQuery(this);
            var field = self.find('.daftplugAdminInputTextarea_field');
            var placeholder = field.attr('data-placeholder');

            field.after('<span class="daftplugAdminInputTextarea_placeholder">' + placeholder + '</span>');

            field.on('change keydown keyup paste', function(e) {
                field.height(0).height(field.prop('scrollHeight') - parseInt(field.css('padding-bottom')) - 5);
            }).change();

            field.on('invalid', function(e) {
                self.addClass('-invalid');
                setTimeout(function(e) {
                    self.removeClass('-invalid');
                }, 2300);
            });
        });

        // Handle checkbox
        daftplugAdmin.find('.daftplugAdminInputCheckbox').each(function(e) {
            var self = jQuery(this);
            var field = self.find('.daftplugAdminInputCheckbox_field');
            var dependentDisableD = daftplugAdmin.find('.-' + field.attr('id') + 'DependentDisableD');
            var dependentHideD = daftplugAdmin.find('.-' + field.attr('id') + 'DependentHideD');
            var dependentDisableE = daftplugAdmin.find('.-' + field.attr('id') + 'DependentDisableE');
            var dependentHideE = daftplugAdmin.find('.-' + field.attr('id') + 'DependentHideE');
            var dependentDisableDField = dependentDisableD.find('[class*="_field"]');
            var dependentDisableEField = dependentDisableE.find('[class*="_field"]');
            var dependentHideDField = dependentHideD.find('[class*="_field"]');
            var dependentHideEField = dependentHideE.find('[class*="_field"]');

            dependentDisableDField.add(dependentDisableEField).add(dependentHideDField).add(dependentHideEField).each(function(e) {
            	if (jQuery(this).is('[required]')) {
            		jQuery(this).attr('data-required', 'true');
            	}
            });

            field.after(`<span class="daftplugAdminInputCheckbox_background"></span>
                         <span class="daftplugAdminInputCheckbox_grabholder"></span>`);

            field.change(function(e) {
            	if (field.is(':checked')) {
            		dependentDisableD.removeClass('-disabled');
                    dependentDisableE.addClass('-disabled');
                    dependentHideD.show();
                    dependentHideE.hide();
                    dependentDisableEField.add(dependentHideEField).prop('required', false);
                    dependentDisableDField.add(dependentHideDField).each(function(e) {
    	        		if (jQuery(this).attr('data-required') == 'true') {
    	        			jQuery(this).prop('required', true);
    	        		} else {
    	        			jQuery(this).prop('required', false);
    	        		}
                    });
            	} else {
    				dependentDisableD.addClass('-disabled');
                    dependentDisableE.removeClass('-disabled');
                    dependentHideD.hide();
                    dependentHideE.show();
            		dependentDisableDField.add(dependentHideDField).prop('required', false);
                    dependentDisableEField.add(dependentHideEField).each(function(e) {
    	        		if (jQuery(this).attr('data-required') == 'true') {
    	        			jQuery(this).prop('required', true);
    	        		} else {
    	        			jQuery(this).prop('required', false);
    	        		}
                    });
            	}
            }).change();
        });

        // Handle number input
        daftplugAdmin.find('.daftplugAdminInputNumber').each(function(e) {
            var self = jQuery(this);
            var field = self.find('.daftplugAdminInputNumber_field');
            var placeholder = field.attr('data-placeholder');
            var step = parseFloat(field.attr('step'));
            var min = parseFloat(field.attr('min'));
            var max = parseFloat(field.attr('max'));

            field.before('<svg class="daftplugAdminInputNumber_icon -iconMinus"><use href="#iconMinus"></use></svg>')
                 .after(`<span class="daftplugAdminInputNumber_placeholder" style="left: 42px;">${placeholder}</span>
                         <svg class="daftplugAdminInputNumber_icon -iconPlus"><use href="#iconPlus"></use></svg>`);

            var icon = self.find('.daftplugAdminInputNumber_icon');

            field.on('focus blur', function(e) {
                if(e.type == 'focus' || e.type == 'focusin') { 
                  icon.addClass('-focused');
                } else{
                  icon.removeClass('-focused');
                }
            });

            self.find('.daftplugAdminInputNumber_icon.-iconMinus').click(function(e) {
                var value = parseFloat(field.val());
                if (value > min) {
                    field.val(value - step).change();
                }
            });

            self.find('.daftplugAdminInputNumber_icon.-iconPlus').click(function(e) {
                var value = parseFloat(field.val());
                if (field.val().length) {
                    if (value < max) {
                        field.val(value + step).change();
                    }
                } else {
                    field.val(step).change(); 
                }
            });

            field.on('invalid', function(e) {
                self.add(icon).addClass('-invalid');
                setTimeout(function(e) {
                    self.add(icon).removeClass('-invalid');
                }, 2300);
            });
        });

        // Handle select input
        daftplugAdmin.find('.daftplugAdminInputSelect').each(function(e) {
            var self = jQuery(this);
            var field = self.find('.daftplugAdminInputSelect_field');
            var fieldOption = field.find('option');
            var label = jQuery('label[for="'+field.attr('id')+'"]');
            var placeholder = field.attr('data-placeholder');

            field.after(`<div class="daftplugAdminInputSelect_dropdown"></div>
                         <span class="daftplugAdminInputSelect_placeholder">${placeholder}</span>
                         <ul class="daftplugAdminInputSelect_list"></ul>
                         <span class="daftplugAdminInputSelect_arrow"></span>`);

            fieldOption.each(function(e) {
                self.find('.daftplugAdminInputSelect_list').append(`<li class="daftplugAdminInputSelect_option" data-value="${jQuery(this).val().trim()}">
                                                                        <a class="daftplugAdminInputSelect_text">${jQuery(this).text().trim()}</a>
                                                                    </li>`);
            });

            var dropdown = self.find('.daftplugAdminInputSelect_dropdown');
            var list = self.find('.daftplugAdminInputSelect_list');
            var option = self.find('.daftplugAdminInputSelect_option');

            dropdown.add(list).attr('data-name', field.attr('name'));

            if (field.is('[multiple]')) {
            	dropdown.attr('data-multiple', 'true');
            	if (!field.find('option:selected').length) {
                    fieldOption.first().prop('selected', true);
                }
                field.find('option:selected').each(function(e) {
                    var self = jQuery(this);
            		dropdown.append(function(e) {
            			return jQuery('<span class="daftplugAdminInputSelect_choice" data-value="'+self.val()+'">'+self.text()+'<svg class="daftplugAdminInputSelect_deselect -iconX"><use href="#iconX"></use></svg></span>').click(function(e) {
			            	var self = jQuery(this);
			                e.stopPropagation();
			                self.remove();
			                list.find('.daftplugAdminInputSelect_option[data-value="'+self.attr('data-value')+'"]').removeClass('-selected');
			                list.css('top', dropdown.height() + 5).find('.daftplugAdminInputSelect_noselections').remove();
			                field.find('option[value="'+self.attr('data-value')+'"]').prop('selected', false);
				            if (dropdown.children(':visible').length === 0) {
				            	dropdown.removeClass('-hasValue');
				            }
            			});
            		}).addClass('-hasValue');
                    list.find('.daftplugAdminInputSelect_option[data-value="'+self.val()+'"]').addClass('-selected');
                });
	            if (!option.not('.-selected').length) {
	            	list.append('<h5 class="daftplugAdminInputSelect_noselections">No Selections</h5>');
	            }
            	list.css('top', dropdown.height() + 5);
            	option.click(function(e) {
            		var self = jQuery(this);
    				e.stopPropagation();
    	        	self.addClass('-selected');
    	        	field.find('option[value="'+self.attr('data-value')+'"]').prop('selected', true);
            		dropdown.append(function(e) {
            			return jQuery('<span class="daftplugAdminInputSelect_choice" data-value="'+self.attr('data-value')+'">'+self.children().text()+'<svg class="daftplugAdminInputSelect_deselect -iconX"><use href="#iconX"></use></svg></span>').click(function(e) {
    		            	var self = jQuery(this);
    		                e.stopPropagation();
    		                self.remove();
    		                list.find('.daftplugAdminInputSelect_option[data-value="'+self.attr('data-value')+'"]').removeClass('-selected');
    		                list.css('top', dropdown.height() + 5).find('.daftplugAdminInputSelect_noselections').remove();
    		                field.find('option[value="'+self.attr('data-value')+'"]').prop('selected', false);
    			            if (dropdown.children(':visible').length === 0) {
    			            	dropdown.removeClass('-hasValue');
    			            }
            			});
            		}).addClass('-hasValue');
    	        	list.css('top', dropdown.height() + 5);
    	            if (!option.not('.-selected').length) {
    	            	list.append('<h5 class="daftplugAdminInputSelect_noselections">No Selections</h5>');
    	            }
            	});
    	        dropdown.add(label).click(function(e) {
    	            e.stopPropagation();
    	            e.preventDefault();
    	            dropdown.toggleClass('-open');
    	            list.toggleClass('-open').scrollTop(0).css('top', dropdown.height() + 5);
    	        });
            } else {
    	        if (field.find('option:selected').length) {
    	            dropdown.attr('data-value', jQuery(this).find('option:selected').val()).text(jQuery(this).find('option:selected').text()).addClass('-hasValue');
    	            list.find('.daftplugAdminInputSelect_option[data-value="'+jQuery(this).find('option:selected').val()+'"]').addClass('-selected');
    	        }
    	        option.click(function(e) {
    	        	var self = jQuery(this);
    	        	option.removeClass('-selected');
                	self.addClass('-selected');
                	fieldOption.prop('selected', false);
                	field.find('option[value="'+self.attr('data-value')+'"]').prop('selected', true);
                	dropdown.text(self.children().text()).addClass('-hasValue');
    	        });
    	        dropdown.add(label).click(function(e) {
    	            e.stopPropagation();
    	            e.preventDefault();
    	            dropdown.toggleClass('-open');
    	            list.toggleClass('-open').scrollTop(0);
    	        });
            }

            jQuery(document).add(daftplugAdmin.find('.daftplugAdminPopup_container')).on('click touch', function(e) {
                if (dropdown.hasClass('-open')) {
                    dropdown.toggleClass('-open');
                    list.removeClass('-open');
                }
            });

            field.on('invalid', function(e) {
            	self.addClass('-invalid');
                setTimeout(function(e) {
                    self.removeClass('-invalid');
                }, 2300);
            });
        });

        // Handle range input
        daftplugAdmin.find('.daftplugAdminInputRange').each(function(e) {
            var self = jQuery(this);
            var field = self.find('.daftplugAdminInputRange_field');
            var val = parseFloat(field.val());
            var min = parseFloat(field.attr('min'));
            var max = parseFloat(field.attr('max'));

            field.after('<output class="daftplugAdminInputRange_output">' + val + '</output>');
            var output = self.find('.daftplugAdminInputRange_output');

            field.on('input change', function(e) {
                var val = parseFloat(field.val());
                var fillPercent = val * 100 / max;
                field.css('background', 'linear-gradient(to right, #4073ff 0%, #4073ff ' + fillPercent + '%, #d9dbde ' + fillPercent + '%)');
                output.text(val);
            }).change();
        });

        // Handle color input
        daftplugAdmin.find('.daftplugAdminInputColor').each(function(e) {
            var self = jQuery(this);
            var field = self.find('.daftplugAdminInputColor_field');
            var label = self.prev('.daftplugAdminField_label');
            var color = field.val();
            var placeholder = field.attr('data-placeholder');

            field.addClass('jscolor {hash:true, required:false}');

            field.after('<span class="daftplugAdminInputColor_placeholder" style="background: '+color+'">' + placeholder + '</span>');
            var elmPlaceholder = self.find('.daftplugAdminInputColor_placeholder');

            label.click(function(e) {
            	document.getElementById(field.attr('id')).jscolor.show();
            });

            field.on('input change', function(e) {
                var color = field.val();
                elmPlaceholder.css('background', color);
            });

            field.on('invalid', function(e) {
                self.addClass('-invalid');
                setTimeout(function(e) {
                    self.removeClass('-invalid');
                }, 2300);
            });
        });

        // Handle upload input
        daftplugAdmin.find('.daftplugAdminInputUpload').each(function(e) {
            var self = jQuery(this);
            var field = self.find('.daftplugAdminInputUpload_field');
            var label = jQuery('label[for="'+field.attr('id')+'"]');
            var mimes = field.attr('data-mimes');
            var maxWidth = field.attr('data-max-width');
            var minWidth = field.attr('data-min-width');
            var maxHeight = field.attr('data-max-height');
            var minHeight = field.attr('data-min-height');
            var imageSrc = field.attr('data-attach-url');
            var frame;

            if (imageSrc) {
                jQuery.ajax({
                    url: imageSrc,
                    type: 'HEAD',
                    error: function() {
                        field.val('');
                        field.removeAttr('data-attach-url');
                    },
                    success: function() {
                        field.addClass('-hasFile');
                    }
                });
            }

            field.after(`<div class="daftplugAdminInputUpload_attach">
                            <div class="daftplugAdminInputUpload_upload">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" class="daftplugAdminInputUpload_icon -iconUpload">
                                    <g stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M32,1 L32,1 C49.1208272,1 63,14.8791728 63,32 L63,32 C63,49.1208272 49.1208272,63 32,63 L32,63 C14.8791728,63 1,49.1208272 1,32 L1,32 C1,14.8791728 14.8791728,1 32,1 Z" id="circleActive"></path>
                                        <path d="M22,26 L22,38 C22,42.418278 25.581722,46 30,46 C34.418278,46 38,42.418278 38,38 L38,20 L36,20 L36,38 C36,41.3137085 33.3137085,44 30,44 C26.6862915,44 24,41.3137085 24,38 L24,26 C24,25.4477153 23.5522847,25 23,25 C22.4477153,25 22,25.4477153 22,26 Z" id="clipBack"></path>
                                        <g id="preview"><image preserveAspectRatio="none" width="30px" height="30px" href=\'${imageSrc}\'></image></g>
                                        <path d="M32,25 C32,24.4477153 32.4477153,24 33,24 C33.5522847,24 34,24.4477153 34,25 L34,38 C34,40.209139 32.209139,42 30,42 C27.790861,42 26,40.209139 26,38 L26,20 C26,16.6862915 28.6862915,14 32,14 C35.3137085,14 38,16.6862915 38,20 L36,20 C36,17.790861 34.209139,16 32,16 C29.790861,16 28,17.790861 28,20 L28,38 C28,39.1045695 28.8954305,40 30,40 C31.1045695,40 32,39.1045695 32,38 L32,25 Z" id="clipFront"></path>
                                    </g>
                                </svg>
                            </div>
                            <div class="daftplugAdminInputUpload_undo">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="daftplugAdminInputUpload_icon -iconUndo">
                                    <g stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="10" cy="10" r="10" id="circle"></circle>
                                        <path d="M7,7 L13,13" id="line"></path>
                                        <path d="M7,13 L13,7" id="line"></path>
                                    </g>
                                </svg>
                            </div>
                        </div>`);

            var upload = self.find('.daftplugAdminInputUpload_upload');
            var undo = self.find('.daftplugAdminInputUpload_undo');
            var preview = self.find('#preview');

            upload.add(label).click(function(e) {
                if (frame) {
                    frame.open();
                    return;
                }

                frame = wp.media({
                    title: 'Select or upload a file',
                    button: {
                        text: 'Select File'
                    },
                    multiple: false
                });

                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    var errors = [];

                    if (mimes !== '') {
                        var mimesArray = mimes.split(',');
                        var fileMime = attachment.subtype;
                        if (jQuery.inArray(fileMime, mimesArray) === -1) {
                            errors.push('This file should be one of the following file types:\n' + mimes);
                        }
                    }

                    if (maxHeight !== '' && attachment.height > maxHeight) {
                        errors.push('Image can\'t be higher than ' + maxHeight + 'px.');
                    }

                    if (minHeight !== '' && attachment.height < minHeight) {
                        errors.push('Image should be at least ' + minHeight + 'px high.');
                    }

                    if (maxWidth !== '' && attachment.width > maxWidth) {
                        errors.push('Image can\'t be wider than ' + maxWidth + 'px.');
                    }

                    if (minWidth !== '' && attachment.width < minWidth) {
                        errors.push('Image should be at least ' + minWidth + 'px wide.');
                    }

                    if (errors.length) {
                        alert(errors.join('\n\n'));
                        return;
                    }

                    if (attachment.type === 'image') {
                        var imageSrc = attachment.url;
                        var image = '<image preserveAspectRatio="none" width="30px" height="30px" href=\'' + imageSrc + '\'></image>';
                    } else {
                        var imageSrc = objectName.fileIcon;
                        var image = '<image preserveAspectRatio="none" width="30px" height="30px" href=\'' + imageSrc + '\'></image>';
                    }

                    field.val(attachment.id).addClass('-active -hasFile');
                    field.attr('data-attach-url', imageSrc);
                    setTimeout(function() {
                        field.removeClass('-active');
                    }, 1000);

                    preview.html(image);
                });

                frame.open();
            });

            undo.click(function(e) {
                field.val('').removeClass('-hasFile');
                field.removeAttr('data-attach-url');
            });

            field.on('invalid', function(e) {
                self.addClass('-invalid');
                setTimeout(function(e) {
                    self.removeClass('-invalid');
                }, 2300);
            });
        });

        // Activate license
        daftplugAdmin.find('.daftplugAdminActivateLicense_form').submit(function(e) {
            e.preventDefault();
            var self = jQuery(this);
            var action = optionName + '_activate_license';
            var nonce = self.attr('data-nonce');
            var purchaseCode = self.find('#purchaseCode').val();
            var button = self.find('.daftplugAdminButton.-submit');
            var responseText = self.find('.daftplugAdminField_response');

            jQuery.ajax({
                url: ajaxurl,
                dataType: 'text',
                type: 'POST',
                data: {
                    action: action,
                    nonce: nonce,
                    purchaseCode: purchaseCode
                },
                beforeSend: function() {
                    button.addClass('-loading');
                },
                success: function(response, textStatus, jqXhr) {
                    if (response == 1) {
                        button.addClass('-success');
                        setTimeout(function() {
                            button.removeClass('-loading -success');
                            daftplugAdmin.find('.daftplugAdminPage.-activation').addClass('-disabled');
                            daftplugAdmin.find('.daftplugAdminLoader').fadeIn('fast');
                            window.location.hash = '#/overview/';
                            window.location.reload();
                        }, 1500);
                    } else {
                        button.addClass('-fail');
                        setTimeout(function() {
                            button.removeClass('-loading -fail');
                        }, 1500);
                        responseText.css({
                            'color': '#FF3A3A',
                            'padding-left': '15px'
                        }).html(response).fadeIn('fast');
                    }
                },
                complete: function() {},
                error: function(jqXhr, textStatus, errorThrown) {
                    button.addClass('-fail');
                    setTimeout(function() {
                        button.removeClass('-loading -fail');
                    }, 1500);
                    responseText.css({
                        'color': '#FF3A3A',
                        'padding-left': '15px'
                    }).html('An unexpected error occurred!').fadeIn('fast');
                }
            });
        });

        // Deactivate license
        daftplugAdmin.find('.daftplugAdminButton.-deactivateLicense').submit(function(e) {
            e.preventDefault();
            var self = jQuery(this);
            var action = optionName + '_deactivate_license';
            var nonce = self.attr('data-nonce');

            jQuery.ajax({
                url: ajaxurl,
                dataType: 'text',
                type: 'POST',
                data: {
                    action: action,
                    nonce: nonce
                },
                beforeSend: function() {
                    self.addClass('-loading');
                    daftplugAdmin.find('.daftplugAdminButton').not(self).add('.daftplugAdminInputCheckbox.-featuresCheckbox').add('.daftplugAdminMenu').addClass('-disabled');
                },
                success: function(response, textStatus, jqXhr) {
                    if (response == 1) {
                        self.addClass('-success');
                        setTimeout(function() {
                            self.removeClass('-loading -success');
                            daftplugAdmin.find('.daftplugAdminHeader').add('.daftplugAdminMain').add('.daftplugAdminFooter').addClass('-disabled');
                            daftplugAdmin.find('.daftplugAdminLoader').fadeIn('fast');
                            window.location.hash = '#/activation/';
                            window.location.reload();
                        }, 1500);
                    } else {
                        self.addClass('-fail');
                        setTimeout(function() {
                            self.removeClass('-loading -fail');
                            daftplugAdmin.find('.daftplugAdminButton').not(self).add('.daftplugAdminInputCheckbox.-featuresCheckbox').add('.daftplugAdminMenu').removeClass('-disabled');
                        }, 1500);
                    }
                },
                complete: function() {},
                error: function(jqXhr, textStatus, errorThrown) {
                    self.addClass('-fail');
                    setTimeout(function() {
                        self.removeClass('-loading -fail');
                        daftplugAdmin.find('.daftplugAdminButton').not(self).add('.daftplugAdminInputCheckbox.-featuresCheckbox').add('.daftplugAdminMenu').removeClass('-disabled');
                    }, 1500);
                }
            });
        });

        // Submit ticket 
        daftplugAdmin.find('.daftplugAdminSupportTicket_form').submit(function(e) {
            e.preventDefault();
            var self = jQuery(this);
            var action = optionName + '_send_ticket';
            var nonce = self.attr('data-nonce');
            var purchaseCode = self.find('#purchaseCode').val();
            var firstName = self.find('#firstName').val();
            var contactEmail = self.find('#contactEmail').val();
            var problemDescription = self.find('#problemDescription').val();
            var wordpressUsername = self.find('#wordpressUsername').val();
            var wordpressPassword = self.find('#wordpressPassword').val();
            var button = self.find('.daftplugAdminButton.-submit');
            var responseText = self.find('.daftplugAdminField_response');

            jQuery.ajax({
                url: ajaxurl,
                dataType: 'text',
                type: 'POST',
                data: {
                    action: action,
                    nonce: nonce,
                    purchaseCode: purchaseCode,
                    firstName: firstName,
                    contactEmail: contactEmail,
                    problemDescription: problemDescription,
                    wordpressUsername: wordpressUsername,
                    wordpressPassword: wordpressPassword
                },
                beforeSend: function() {
                    button.addClass('-loading');
                },
                success: function(response, textStatus, jqXhr) {
                    if (response == 1) {
                        self.trigger("reset");
                        button.addClass('-success');
                        setTimeout(function() {
                            button.removeClass('-loading -success');
                        }, 1500);
                        responseText.css({
                            'color': '#4073FF',
                            'padding-left': '15px'
                        }).html('Thank you! We will send our response as soon as possible to your email address.').fadeIn('fast');
                    } else {
                        button.addClass('-fail');
                        setTimeout(function() {
                            button.removeClass('-loading -fail');
                        }, 1500);
                        responseText.css('color', '#FF3A3A').html('Submission failed. Please use the <a target="_blank" href="https://codecanyon.net/user/daftplug#contact">Contact Form</a> found on our Codecanyon profile page instead.').fadeIn('fast');
                    }
                },
                complete: function() {},
                error: function(jqXhr, textStatus, errorThrown) {
                    button.addClass('-fail');
                    setTimeout(function() {
                        button.removeClass('-loading -fail');
                    }, 1500);
                    responseText.css('color', '#FF3A3A').html('Submission failed. Please use the <a target="_blank" href="https://codecanyon.net/user/daftplug#contact">Contact Form</a> found on our Codecanyon profile page instead.').fadeIn('fast');
                }
            });
        });

        // Save settings
        daftplugAdmin.find('.daftplugAdminSettings_form').submit(function(e) {
            e.preventDefault();
            var self = jQuery(this);
            var button = self.find('.daftplugAdminButton.-submit');
            var action = optionName + '_save_settings';
            var nonce = self.attr('data-nonce');
            var settings = self.daftplugSerialize();

            jQuery.ajax({
                url: ajaxurl,
                dataType: 'text',
                type: 'POST',
                data: {
                    action: action,
                    nonce: nonce,
                    settings: settings
                },
                beforeSend: function() {
                    button.addClass('-loading');
                },
                success: function(response, textStatus, jqXhr) {
                    if (response == 1) {
                        button.addClass('-success');
                        setTimeout(function() {
                            button.removeClass('-loading -success');
                        }, 1500);
                    } else {
                        button.addClass('-fail');
                        setTimeout(function() {
                            button.removeClass('-loading -fail');
                        }, 1500);
                    }
                },
                complete: function() {
                },
                error: function(jqXhr, textStatus, errorThrown) {
                    button.addClass('-fail');
                    setTimeout(function() {
                        button.removeClass('-loading -fail');
                    }, 1500);
                }
            });
        });

        // Save plugin features settings
        daftplugAdmin.find('.daftplugAdminInputCheckbox.-featuresCheckbox').each(function(e) {
	        var self = jQuery(this);
	        var field = self.find('.daftplugAdminInputCheckbox_field');
	        var fieldset = jQuery('.daftplugAdminPluginFeatures');

	        field.on('click', function(e) {
	            e.preventDefault();
	            var action = optionName + '_save_settings';
	            var nonce = self.attr('data-nonce');
	            var settings = fieldset.daftplugSerialize();

	            jQuery.ajax({
	                url: ajaxurl,
	                dataType: 'text',
	                type: 'POST',
	                data: {
	                    action: action,
	                    nonce: nonce,
	                    settings: settings
	                },
	                beforeSend: function() {
	                    self.addClass('-loading');
	                    daftplugAdmin.find('.daftplugAdminInputCheckbox.-featuresCheckbox').not(self).parent().add('.daftplugAdminButton').add('.daftplugAdminMenu').addClass('-disabled');
	                },
	                success: function(response, textStatus, jqXhr) {
	                    if (response == 1) {
		                    setTimeout(function() {
		                        self.removeClass('-loading');
		                        daftplugAdmin.find('.daftplugAdminInputCheckbox.-featuresCheckbox').not(self).parent().removeClass('-disabled');
		                        if (field.is(':checked')) {
		                        	field.prop('checked', false);
		                        } else {
		                        	field.prop('checked', true);
		                        }
		                        daftplugAdmin.find('.daftplugAdminHeader').add('.daftplugAdminMain').add('.daftplugAdminFooter').addClass('-disabled');
                                daftplugAdmin.find('.daftplugAdminLoader').fadeIn('fast');
		                        window.location.reload();
		                    }, 1500);
	                    } else {
		                    setTimeout(function() {
		                        self.removeClass('-loading');
		                        daftplugAdmin.find('.daftplugAdminInputCheckbox.-featuresCheckbox').not(self).parent().add('.daftplugAdminButton').add('.daftplugAdminMenu').removeClass('-disabled');
		                        if (field.is(':checked')) {
		                        	field.prop('checked', true);
		                        } else {
		                        	field.prop('checked', false);
		                        }
	                        }, 1500);
	                    }
	                },
	                complete: function() {
	                },
	                error: function(jqXhr, textStatus, errorThrown) {
	                    setTimeout(function() {
	                        self.removeClass('-loading');
	                        daftplugAdmin.find('.daftplugAdminInputCheckbox.-featuresCheckbox').not(self).parent().add('.daftplugAdminButton').add('.daftplugAdminMenu').removeClass('-disabled');
	                        if (field.is(':checked')) {
	                        	field.prop('checked', true);
	                        } else {
	                        	field.prop('checked', false);
	                        }
                        }, 1500);
	                }
	            });
	        });
        });

        // Generate PWA installs area chart
        jQuery.ajax({
            url: ajaxurl,
            dataType: 'json',
            type: 'POST',
            data: {
                action: optionName + '_get_installation_analytics',
            },
            beforeSend: function() {

            },
            success: function(response, textStatus, jqXhr) {
                var ctx = document.getElementById("daftplugAdminInstallationAnalytics_chart");
                var labels = response.dates;
                var data = response.data;
                var reactionsAreaChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: "Installs",
                            lineTension: 0.3,
                            backgroundColor: "rgba(78, 115, 223, 0.05)",
                            borderColor: "rgba(78, 115, 223, 1)",
                            pointRadius: 3,
                            pointBackgroundColor: "rgba(78, 115, 223, 1)",
                            pointBorderColor: "rgba(78, 115, 223, 1)",
                            pointHoverRadius: 3,
                            pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                            pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                            pointHitRadius: 10,
                            pointBorderWidth: 2,
                            data: data,
                        }],
                    },
                    options: {
                        maintainAspectRatio: false,
                        layout: {
                            padding: {
                                top: 10
                            }
                        },
                        scales: {
                            xAxes: [{
                                time: {
                                    unit: 'date'
                                },
                                gridLines: {
                                    display: false,
                                    drawBorder: false
                                },
                                ticks: {
                                    maxTicksLimit: 7,
                                    padding: 10
                                }
                            }],
                            yAxes: [{
                                ticks: {
                                    maxTicksLimit: 5,
                                    padding: 10,
                                    beginAtZero: true,
                                    callback: function(value) {if (value % 1 === 0) {return value;}}
                                },
                                gridLines: {
                                    color: "rgb(234, 236, 244)",
                                    zeroLineColor: "rgb(234, 236, 244)",
                                    drawBorder: false,
                                    borderDash: [2],
                                    zeroLineBorderDash: [2]
                                }
                            }],
                        },
                        legend: {
                            display: false
                        },
                        tooltips: {
                            backgroundColor: "rgb(255,255,255)",
                            bodyFontColor: "#858796",
                            titleMarginBottom: 10,
                            titleFontColor: '#6e707e',
                            titleFontSize: 14,
                            borderColor: '#dddfeb',
                            borderWidth: 1,
                            xPadding: 15,
                            yPadding: 15,
                            displayColors: false,
                            intersect: false,
                            mode: 'index',
                            caretPadding: 10
                        }
                    }
                });
            },
            complete: function() {

            },
            error: function(jqXhr, textStatus, errorThrown) {

            }
        });

		// Handle review modal
		daftplugAdmin.find('[data-popup="reviewModal"]').each(function(e) {
			var self = jQuery(this);
			var secondsSpent = Number(localStorage.getItem('secondsSpent'));
			setInterval(function() {
			    localStorage.setItem('secondsSpent', ++secondsSpent);
			    if (secondsSpent == 60) {
			        self.addClass('-active');
			    }
			}, 1000);
		});
    });

	// Helpers
	jQuery.fn.daftplugSerialize = function() {
	    var o = {};
	    var a = this.serializeArray();
	    jQuery.each(a, function() {
	        if (o[this.name] !== undefined) {
	            if (!o[this.name].push) {
	                o[this.name] = [o[this.name]];
	            }
	            o[this.name].push(this.value || '');
	        } else {
	            o[this.name] = this.value || '';
	        }
	    });
	    var radioCheckbox = jQuery('input[type=radio], input[type=checkbox]', this);
	    jQuery.each(radioCheckbox, function() {
	        if(!o.hasOwnProperty(this.name)) {
	            o[this.name] = 'off';
	        }
	    });

	    return JSON.stringify(o);
	};
})(jQuery);
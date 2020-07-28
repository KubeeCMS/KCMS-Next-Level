self.addEventListener('push', event => {
    if (event.data) {
		const pushData = event.data.json();
		event.waitUntil(
			self.registration.showNotification(pushData.title, pushData)
		);
		console.log(pushData);
    } else {
    	console.log('No push data fetched');
    }
});

self.addEventListener('notificationclick', event => {
	event.notification.close();
  	if (event.action === 'action1') {  
		event.waitUntil(
			clients.openWindow(event.notification.data.pushActionbutton1Url)
		);
  	} else if (event.action === 'action2') {
		event.waitUntil(
			clients.openWindow(event.notification.data.pushActionbutton2Url)
		);
  	} else {
		event.waitUntil(
			clients.openWindow(event.notification.data.url)
		);
  	}
});
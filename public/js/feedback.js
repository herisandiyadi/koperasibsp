try {
	let errors = flashed.errors;
	if (errors.length > 1) {
		errors.forEach(function (val, index) {
			PNotify.error({
				text: val
			});
		})
	}

	if (errors.length == 1) {
		PNotify.error({
			text: errors[0]
		});
	}
	//
	// if(flashed.success > 1)
	// {
	//     flashed.success.forEach(function(val,index){
	//         PNotify.success({
	//             text: val
	//         });
	//     })
	// }

	if (flashed.success.length != 0) {
		PNotify.success({
			text: flashed.success
		});
	}

	// if(flashed.warning > 1)
	// {
	//     flashed.warning.forEach(function(val,index){
	//         PNotify.warning({
	//             text: val
	//         });
	//     })
	// }

	if (flashed.notice.length != 0) {
		PNotify.notice({
			text: flashed.notice
		});
	}
	if (flashed.warning.length != 0) {
		PNotify.notice({
			text: flashed.warning,
		});
	}
	//
	// if(flashed.info > 1)
	// {
	//     flashed.info.forEach(function(val,index){
	//         PNotify.warning({
	//             text: val
	//         });
	//     })
	// }

	if (flashed.info.length != 0) {
		PNotify.info({
			text: flashed.info
		});
	}
}
catch (e) {
	if (e.name == "ReferenceError") {
		barIsDeclared = false;
	}
}

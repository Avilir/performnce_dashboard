
function ReloadFormValues() {
	/* 
		this function reload the form when value of the Version is changed
		so the values of the build will be related to the version number.
	*/
	var str = '';
	var elem = document.getElementById('main_form').elements;
	for (var i = 0; i < elem.length; i++) {
		if (elem[i].name != 'submit') {
			str += elem[i].name + "=" + elem[i].value + "&";
		}
	}
	self.location = 'index.php?' + str.slice(0, -1);
}

function LastBuildRun() {
	/* 
		this function reload the form when value of the platform for last build 
		is changed so the values of the build will be related to the version number.
	*/
	var elem = document.getElementById('init_form').elements;
	self.location = 'index.php?build_run=' + elem[2].value;
}

function LastVerReport() {
	/* 
		this function reload the form when value of the platform for last version 
		is changed so the values of the build will be related to the version number.
	*/
	var elem = document.getElementById('init_form').elements;
	self.location = 'index.php?ver_repo=' + elem[1].value;
}

function full_report() {
	/*
		this function reload the form when value of the topology for last build or
		last version is changed so the values of the build will be related to the
		version number.
	*/
	var url_string = window.location.href
	var url = new URL(url_string);
	var c = url.searchParams.get("build_run");
	var d = url.searchParams.get("ver_repo");

	var elem = document.getElementById('init_form').elements;
	self.location = 'index.php?ver_repo=' + d + "&build_run=" + c + "&az_topology=" + elem[3].value + "&submit";
}

function Compare() {
	/* 
		this function reload the form when value of the Version is changed
		so the values of the build will be related to the version number.
	*/
	var elem = document.getElementById('init_form').elements;
	self.location = 'index.php?compare=' + elem[2].value + "&submit";
}

function openTest(evt, testName) {
	/* 
		this function create a 'Tab View" for test tests results.
		each test will be display in a different  tab.
	*/
	var i, tabcontent, tablinks;
	tabcontent = document.getElementsByClassName("tabcontent");
	for (i = 0; i < tabcontent.length; i++) {
		tabcontent[i].style.display = "none";
	}
	tablinks = document.getElementsByClassName("tablinks");
	for (i = 0; i < tablinks.length; i++) {
		tablinks[i].className = tablinks[i].className.replace(" active", "");
	}
	document.getElementById(testName).style.display = "block";
	evt.currentTarget.className += " active";
}

function openFirstTest(testName) {
	/*
		this function set the first test results tab as active
	*/
	var i, tabcontent, tablinks;
	tabcontent = document.getElementsByClassName("tabcontent");
	for (i = 0; i < tabcontent.length; i++) {
		tabcontent[i].style.display = "none";
	}
	tablinks = document.getElementsByClassName("tablinks");
	for (i = 0; i < tablinks.length; i++) {
		tablinks[i].className = tablinks[i].className.replace(" active", "");
	}
	document.getElementById(testName).style.display = "block";
	evt.currentTarget.className += " active";
}

var logScroll={
	container: document.getElementById('container'), 
	dummy_scroll: document.getElementById('dummy_scroll'), 
	logs: document.getElementsByClassName('log'),
	separateBox: document.getElementsByClassName('separate-box')[0],
	flag:0,
	stopFlag:0,
	ScrollControl:(e)=>{
		var self = logScroll;
		if(self.flag == 0){
			self.ScrollBottom2();
		}else if(self.flag == 1){
			self.ScrollTop();
		}
	},
	ScrollTop:(e)=>{
		var self = logScroll;
		self.container.scrollIntoView({
			behavior : 'smooth',
			block    : 'start',
			inline   : 'start'
		});
		self.flag = 0;
		self.dummy_scroll.classList.remove("fa-angles-up");
		self.dummy_scroll.classList.add("fa-angles-down");
		self.stopFlag = 1;
	},
	ScrollBottom:(e)=>{
		var self = logScroll;
		self.logs = document.getElementsByClassName('log')
		var maxNum = self.logs.length - 1;
		self.logs[maxNum].scrollIntoView({
			behavior : 'smooth',
			block    : 'end',
			inline   : 'start'
		});
		self.flag = 1;
		self.dummy_scroll.classList.remove("fa-angles-down");
		self.dummy_scroll.classList.add("fa-angles-up");
		self.stopFlag = 0;
	},
	ScrollBottom2:(e)=>{
		var self = logScroll;
		self.separateBox.scrollIntoView({
			behavior : 'smooth',
			block    : 'end',
			inline   : 'start'
		});
		self.flag = 1;
		self.dummy_scroll.classList.remove("fa-angles-down");
		self.dummy_scroll.classList.add("fa-angles-up");
		self.stopFlag = 0;
	}
}
window.chartColors = {
	blue: 'rgb(32, 96, 255)',
	cyan: 'rgb(96, 200, 255)',
	green: 'rgb(96, 192, 96)',
	grey: 'rgb(160,160,160)',
	magenta: 'rgb(200, 96, 200)',
	orange: 'rgb(255, 179, 0)',
	purple: 'rgb(153, 102, 255)',
	red: 'rgb(255, 99, 132)',
	yellow: 'rgb(255, 205, 86)'
};

window.randomScalingFactor = function() {
	return (Math.random() > 0.5 ? 1.0 : -1.0) * Math.round(Math.random() * 100);
}
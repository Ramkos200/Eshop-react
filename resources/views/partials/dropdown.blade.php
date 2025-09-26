<script>
		// Dropdown functionality
		document.addEventListener('DOMContentLoaded', function() {
				const dropdowns = document.querySelectorAll('.dropdown');

				dropdowns.forEach(dropdown => {
						dropdown.addEventListener('click', function(e) {
								e.stopPropagation();
								const menu = this.querySelector('.dropdown-menu');
								const isHidden = menu.classList.contains('hidden');

								// Close all other dropdowns
								document.querySelectorAll('.dropdown-menu').forEach(m => {
										m.classList.add('hidden', 'opacity-0');
								});

								// Toggle this dropdown
								if (isHidden) {
										menu.classList.remove('hidden');
										setTimeout(() => {
												menu.classList.remove('opacity-0');
										}, 10);
								} else {
										menu.classList.add('opacity-0');
										setTimeout(() => {
												menu.classList.add('hidden');
										}, 300);
								}
						});
				});

				// Close dropdowns when clicking elsewhere
				document.addEventListener('click', function() {
						document.querySelectorAll('.dropdown-menu').forEach(menu => {
								menu.classList.add('opacity-0');
								setTimeout(() => {
										menu.classList.add('hidden');
								}, 300);
						});
				});
		});
</script>

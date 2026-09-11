<template>
	<main class="rickroll-page">
		<div class="video-frame">
			<iframe
				src="https://www.youtube.com/embed/dQw4w9WgXcQ?autoplay=1"
				title="Rick Astley - Never Gonna Give You Up"
				allow="autoplay; encrypted-media; picture-in-picture"
				allowfullscreen
			></iframe>
		</div>
	</main>
</template>

<style scoped>
.rickroll-page {
	min-height: calc(100vh - 56px);
	display: flex;
	align-items: center;
	justify-content: center;
	padding: 24px;
	background: #111;
}

.video-frame {
	width: min(900px, 100%);
	aspect-ratio: 16 / 9;
}

iframe {
	width: 100%;
	height: 100%;
	border: 0;
}

@media (max-width: 576px) {
	.rickroll-page {
		min-height: calc(100vh - 56px);
		padding: 16px;
	}
}
</style>

<template>
	<div class="sponsors" :data-status="status">
		<div v-for="sponsor in data" :key="sponsor.name">
			<span v-if="sponsor.star" class="star">
				★
			</span>
			<a :href="sponsor.website" target="_blank" rel="noopener">
				<img :src="getImage(sponsor)" width="100">
			</a>
		</div>
	</div>
</template>

<script lang="ts" setup>
import type { PropType } from 'vue';
import { onMounted, ref } from 'vue';
const { status } = defineProps({
	/**
	 * Status to filter sponsors by
	 */
	status: {
		type: String as PropType<'sponsor' | 'backer' | 'backerPlus'>,
		required: true,
	},
});

const data = ref();
onMounted(async () => {
	const response = await fetch('backers.json').then((response) => {
		return response.json();
	});

	data.value = response
		.filter((sponsor: any) => {
			return sponsor.status === status;
		})

		// sort by star, rank, monthlyContribution then totalAmountDonated
		.sort((a: any, b: any) => {
			return (b.star - a.star) ||
			(b.rank - a.rank) ||
			(b.monthlyContribution - a.monthlyContribution) ||
			(b.totalAmountDonated - a.totalAmountDonated);
		});
});

const getImage = (sponsor: any) => {
	// $src = $member['image'] ?? (strtr($member['profile'], array('https://opencollective.com/' => 'https://images.opencollective.com/')) . '/avatar/256.png');
	if (sponsor.image) {
		return sponsor.image;
	}

	return `${sponsor.profile.replace(
		'https://opencollective.com/',
		'https://images.opencollective.com/'
	)}/avatar/256.png`;
};
</script>

<style scoped>
.sponsors {
	display: flex;
	flex-wrap: wrap;
	gap: 1rem;
	margin-top: 2rem;
background-color: #d1d1d1;
	padding: 1rem;

	img {
		object-fit: contain;
		max-height: 100%;
	}

	&[data-status="backerPlus"] > div {
		width: 42px;
		aspect-ratio: 1/1;

		> a {
			display: block;
			border-radius: 50%;
			overflow: hidden;
			height: 100%;
			width: 100%;
		}
	}

	&[data-status="backer"] > div {
		width: 32px;
		aspect-ratio: 1/1;

		> a {
			display: block;
			border-radius: 50%;
			overflow: hidden;
			height: 100%;
			width: 100%;
		}
	}

	&[data-status="sponsor"] > div {
		border: 0.3em solid var(--vp-c-divider);
		border-radius: 0.1rem;
		display: flex;
		align-items: center;
		justify-content: center;
		width: 64px;
		aspect-ratio: 1/1;
		position: relative;

		> a {
			display: block;
			height: 100%;
			width: 100%;
		}

		.star {
			position: absolute;
			color: gold;
			font-size: 1.5rem;
			top: 0;
			right: 0;
			translate: 50% -50%;
			/* shadow */
			text-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
		}
	}
}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/7.8.5/d3.min.js" integrity="sha512-M7nHCiNUOwFt6Us3r8alutZLm9qMt4s9951uo8jqO4UwJ1hziseL6O3ndFyigx6+LREfZqnhHxYjKRJ8ZQ69DQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<style>
	.link {
		fill: none;
		stroke: #ccc;
		stroke-width: 1.5px;
	}

	.node circle {
		fill: #fff;
		stroke: steelblue;
		stroke-width: 1.5px;
	}

	.node text {
		font-size: 12px;
	}
</style>

	<?php //import("apps/view/inc/sidebar.php"); ?>

	
			<?php
			if (authenticate() == true) {

				$pvctrl = new Pv_ctrl;
				$pvctrl->db = new Dbobjects;
				$tree = $pvctrl->my_tree($_SESSION['user_id']);
			}
			?>
			<section>
				<!-- <?php echo json_encode($tree); ?> -->
				<div style="overflow-y: scroll;" id="treeContainer"></div>

				<script>
					const data = <?php echo json_encode($tree); ?>;

					const width = 800;
					const height = 600;

					const svg = d3.select('#treeContainer')
						.append('svg')
						.attr('width', width)
						.attr('height', height)
						.append('g')
						.attr('transform', 'translate(40, 40)');

					const treeLayout = d3.cluster().size([width - 160, height - 80]);

					const hierarchy = d3.hierarchy(data[0], d => d.tree);

					treeLayout(hierarchy);

					const links = hierarchy.links();
					svg.selectAll('.link')
						.data(links)
						.enter().append('path')
						.attr('class', 'link')
						.attr('d', d => `M${d.source.y},${d.source.x}C${(d.source.y + d.target.y) / 2},${d.source.x} ${(d.source.y + d.target.y) / 2},${d.target.x} ${d.target.y},${d.target.x}`);

					const nodes = svg.selectAll('.node')
						.data(hierarchy.descendants())
						.enter().append('g')
						.attr('class', 'node')
						.attr('transform', d => `translate(${d.y},${d.x})`);

					nodes.append('circle')
						.attr('r', 5);

					nodes.append('text')
						.attr('dy', '.35em')
						.attr('x', d => d.children ? 15 : -15)
						.style('text-anchor', d => d.children ? 'start' : 'end')
						.text(d => d.data.username);
				</script>


<div id="treeContainer"></div>

    <script>
        const data = <?php echo json_encode($tree); ?>;
        const width = 800;
        const height = 600;

        const svg = d3.select('#treeContainer')
            .append('svg')
            .attr('width', width)
            .attr('height', height)
            .append('g')
            .attr('transform', `translate(${width / 2},${height / 2})`);

        const treeLayout = d3.tree()
            .size([360, width / 2 - 80]);

        const root = d3.hierarchy(data[0]);

        treeLayout(root);

        const link = svg.selectAll('.link')
            .data(root.links())
            .enter().append('path')
            .attr('class', 'link')
            .attr('d', d3.linkRadial()
                .angle(d => d.x)
                .radius(d => d.y));

        const node = svg.selectAll('.node')
            .data(root.descendants())
            .enter().append('g')
            .attr('class', d => 'node' + (d.children ? ' node--internal' : ' node--leaf'))
            .attr('transform', d => `translate(${d.y},${d.x})`);

        node.append('circle')
            .attr('r', 5);

        node.append('text')
            .attr('dy', '0.31em')
            .attr('x', d => d.x < 180 === !d.children ? 6 : -6)
            .attr('text-anchor', d => d.x < 180 === !d.children ? 'start' : 'end')
            .attr('transform', d => d.x >= 180 ? 'rotate(180)' : null)
            .text(d => d.data.username);

        node.on('click', (event, d) => {
            toggle(d);
            update(d);
        });

        function toggle(d) {
            if (d.children) {
                d._children = d.children;
                d.children = null;
            } else {
                d.children = d._children;
                d._children = null;
            }
        }

        function update(source) {
            const duration = d3.event && d3.event.altKey ? 2500 : 250;
            const nodes = root.descendants().reverse();
            const links = root.links();

            treeLayout(root);

            let left = root;
            let right = root;
            root.eachBefore(node => {
                if (node.x < left.x) left = node;
                if (node.x > right.x) right = node;
            });

            const height = right.x - left.x + 80;
            const transition = svg.transition()
                .duration(duration)
                .attr('transform', `translate(${width / 2},${height / 2})`);

            node.transition(transition)
                .attr('transform', d => `translate(${project(d.x, d.y)})`);

            link.transition(transition)
                .attr('d', d3.linkRadial()
                    .angle(d => d.x)
                    .radius(d => d.y));

            node.each(function(d) {
                d.x0 = d.x;
                d.y0 = d.y;
            });
        }

        function project(x, y) {
            const angle = (x - 90) / 180 * Math.PI;
            const radius = y;
            return [radius * Math.cos(angle), radius * Math.sin(angle)];
        }
    </script>

			</section>
	
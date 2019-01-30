

<h3>Solicitação de Cópias <b style="color:#27ae60">finalizadas/recebidas pelo solicitante</b> no período de <b style="color:#e74c3c"><?= date('d/m/Y', strtotime($_GET['relat_datainicio'])); ?></b> até <b style="color:#e74c3c"><?= date('d/m/Y', strtotime($_GET['relat_datafim'])); ?></b></h3>
<div class="bs-example" data-example-id="striped-table"> 
	<table class="table table-striped"> 
		<thead> 
			<tr> 
				<th>Cód.</th> 
				<th>Unidade</th> 
				<th>Centro de Custo</th> 
				<th>Descrição</th>
				<th>Acabamento</th>
				<th>Originais</th>
				<th>Exemplares</th>
				<th>Mono</th>
				<th>Color</th>
				<th>Cópias</th>
			</tr> 
		</thead> 
		<tbody>
			<?php
				$sumOriginais = 0;
				$sumExemplares = 0;
				$sumMono = 0;
				$sumColor = 0;
				$sumCopias = 0;
			?>
			<?php foreach ($copias as $copia): ?>
			<tr> 
				<td><?=$copia['matc_id']; ?></td>
				<td><?=$copia['unidade']['uni_nomeabreviado']; ?></td> 
				<td><?=$copia['matc_centrocusto']; ?></td> 
				<td><?= substr($copia['item_descricao'], 0, 100); ?></td>
				<td><?=$copia['acabamento']; ?></td>
				<td><?=$copia['item_qtoriginais']; ?></td> 
				<td><?=$copia['item_qtexemplares']; ?></td>
				<td><?=$copia['item_mono']; ?></td> 
				<td><?=$copia['item_color']; ?></td>
				<td><?=$copia['item_qteCopias']; ?></td>
				
				<?php 
					$sumOriginais  += $copia['item_qtoriginais'];
					$sumExemplares += $copia['item_qtexemplares'];
					$sumMono       += $copia['item_mono'];
					$sumColor      += $copia['item_color'];
					$sumCopias     += $copia['item_qteCopias'];
				?>
			</tr>
			<?php endforeach; ?> 
		</tbody>
	    <tfoot>
	            <tr class="warning kv-edit-hidden" style="border-top: #dedede">
	              <th colspan="4" >Total </th>
	               <th ><?= $sumOriginais; ?></th>
	               <th ><?= $sumExemplares; ?></th>
	               <th ><?= $sumMono; ?></th>
	               <th ><?= $sumColor; ?></th>
	               <th ><?= $sumCopias; ?></th>
	            </tr>
	    </tfoot>
	</table> 
</div>
<style>
  .js-plotly-plot .plotly .modebar{
    right: 20px;
  }
</style>
<div id="<?=$report->graphId();?>_container" class="report-container">
  <?php if (method_exists($report, 'renderSearch')):?>
    <i class="mdl-color-text--blue-grey-400 material-icons search-icon" role="presentation">search</i>
    <?= $report->renderSearch(); ?>
  <?php else: ?>
    <form class="report-search-form mdl-color-text--grey-600" action="/report/reportData">
      <input type="hidden" name="report" value="<?= $report->getApp()->getRequest()->getQuery('report'); ?>" />
      <input type="hidden" name="template" value="<?= $report->getApp()->getRequest()->getQuery('template'); ?>" />
    </form>
  <?php endif;?>
  <div id="<?=$report->graphId();?>" class="chart-container"></div>
</div>
<script>
  var report  = document.getElementById('<?=$report->graphId()?>');
  Plotly.newPlot('<?=$report->graphId();?>',
    <?= $report->tracesJson();?>,
    JSON.parse('<?= $report->plotlyConfig();?>'),
    {
      modeBarButtonsToRemove: ['sendDataToCloud','zoom2d', 'pan', 'pan2d',
        'autoScale2d', 'select2d', 'lasso2d', 'zoomIn2d', 'zoomOut2d',
        'orbitRotation', 'tableRotation', 'toggleSpikelines',
        'resetScale2d', 'hoverClosestCartesian', 'hoverCompareCartesian'],
      responsive: true,
      displaylogo: false,
    }
  );
  report.on('plotly_click',function(data){
      var pts = '';
      for(var i=0; i < data.points.length; i++){
          pts = 'x = '+data.points[i].x +'\ny = '+
              data.points[i].y + '\n\n';
      }
      alert('Closest point clicked:\n\n'+pts);
  });
</script>


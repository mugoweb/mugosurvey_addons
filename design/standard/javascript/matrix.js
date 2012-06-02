function generateMatrix( prefix, question, attribute )
{
    var ColumnValues = Array();
    var RowValues = Array();
    $( '#matrixtable_' + question + '_' + attribute + ' input[for=column]' ).each(function() {
        ColumnValues[ $(this).attr( 'column' ) ] = this.value;
    });
    $( '#matrixtable_' + question + '_' + attribute + ' input[for=row]' ).each(function() {
        RowValues[ $(this).attr( 'row' ) ] = this.value;
    });
    var rows = parseInt( $( '#matrixrows_' + question + '_' + attribute ).val() ) + 1;
    var cols = $( '#matrixcols_' + question + '_' + attribute ).val();

    var content = '<table cellpadding="0" cellspacing="0">';
    for( var i=0;i<rows;i++ )
    {
        content += '<tr><td class="full">';
        if( i > 0 )
        {
            (RowValues[i-1]===undefined)?(RowValues[i-1]=''):(1);
            content += 'row ' + (i) + ':<br /><input for="row" row="' + (i-1) + '" class="matrix-input" name="' + prefix + '_ezsurvey_question_' + question + '_text2_' + attribute + '[1][' + (i-1) + ']" value="' + RowValues[i-1] + '" />';
        }
        content += '</td>';
        for( var j=0;j<cols;j++ )
        {
            if( i == 0 )
            {
                (ColumnValues[j]===undefined)?(ColumnValues[j]=''):(1);
                content += '<td class="full">column ' + (j+1) + ':<br /><input for="column" column="' + j + '" class="matrix-input" name="' + prefix + '_ezsurvey_question_' + question + '_text2_' + attribute + '[0][' + j + ']" value="' + ColumnValues[j] + '" /></td>';
            }
            else
            {
                content += '<td>&nbsp;</td>';
            }
        }
        content += '</tr>';
    }
    content += '</table>';
    $('#matrixtable_' + question + '_' + attribute ).html(content);
}
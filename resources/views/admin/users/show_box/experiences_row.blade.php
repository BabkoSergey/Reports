<div class="box box-default box-solid jq_experience-row jq_experience-row-{{ $experience->id ?? '' }}" data-experience_id="{{ $experience->id ?? '' }}">
    <div class="box-header with-border">
        <h3 class="box-title">{{ $experience->project ?? '' }}</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-primary btn-sm jq_skills-cat-btn-add"><i class="fa fa-plus-square-o color-wite"></i></button>
            <button type="button" class="btn btn-primary btn-sm jq_skills-cat-btn-add"><i class="fa fa-plus-square-o color-wite"></i></button>
            <button type="button" class="btn btn-box-tool jq_skills-cat-btn-expand" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <div class="box-body js-experience-box-body">
        <table class="table table-striped bg-gray"> 
            <tbody>
                <tr>
                    <th style="width: 15%;">{{ __('Positions') }}</th>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
</div> 
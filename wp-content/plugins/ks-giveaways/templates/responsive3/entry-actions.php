  <div data-ng-controller="entryActions" class="small-12 columns ng-cloak">
    <h5 class="text-center" data-ng-if="entryActions.length"><?php _e('Get even more entries!', KS_GIVEAWAYS_TEXT_DOMAIN); ?></h5>
    <div data-ng-repeat="action in entryActions" style="clear:both;" ng-controller="entryAction" ng-init="init(action)">
      <div class="row collapse entry-action-row" data-ng-class="{completed: actionAwarded()}">
        <a class="small-1 columns icon" data-ng-class="getIconClass()" data-ng-href="{{getActionUrl()}}" target="_blank" data-ng-click="handleActionClick($event)"></a>
        <div class="small-10 columns description">
          <a data-ng-href="{{getActionUrl()}}" target="_blank" data-ng-click="handleActionClick($event);" style="display:block;">{{action.text}}</a>
          <input data-ng-if="action.type === 'input'" type="text" name="">
        </div>
        <a class="small-1 columns entries" data-ng-href="{{getActionUrl()}}" target="_blank" data-ng-click="handleActionClick($event)">
          <span ng-if="actionAwarded(action)" class="icon-ei-check"></span>
          <span ng-if="!actionAwarded(action)" ng-bind=" '+' + (action.entries - getContestantActionEntries(action))"></span>
        </a>
      </div>
      <div class="row collapse text-center" data-ng-show="showDetails()">
        <div class="small-12 columns embed-responsive embed-responsive-16by9">
          <youtube-video video-url="getYouTubeVideoUrl()" player-width="'100%'" player="youTubePlayer" player-vars="youTubePlayerVars" class="embed-responsive-item"></youtube-video>
        </div>
      </div>
    </div>
  </div>
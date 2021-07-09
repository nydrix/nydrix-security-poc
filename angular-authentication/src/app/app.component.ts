import { Component, OnDestroy, OnInit } from '@angular/core';
import { MsalBroadcastService } from '@azure/msal-angular';
import { EventMessage, EventType } from '@azure/msal-browser';
import { Subject } from 'rxjs';
import { filter, takeUntil } from 'rxjs/operators';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css'],
})
export class AppComponent implements OnInit, OnDestroy {
  title = 'angular-authentication';

  private readonly _destroying$ = new Subject<void>();

  constructor(private msalBroadcastService: MsalBroadcastService) {}

  public ngOnInit(): void {
    this.msalBroadcastService.msalSubject$
      .pipe(
        filter(
          (msg: EventMessage) => msg.eventType === EventType.LOGIN_SUCCESS
        ),
        takeUntil(this._destroying$)
      )
      .subscribe((result) => {
        console.log(result);
      });
  }

  public ngOnDestroy(): void {
    this._destroying$.next(undefined);
    this._destroying$.complete();
  }
}

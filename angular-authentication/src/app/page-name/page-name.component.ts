import { Component, OnInit } from '@angular/core';
import { MsalService } from '@azure/msal-angular';
import { Observable } from 'rxjs';

import { MembersService } from '../services/members.service';
import { MsalUserService } from '../services/msal-user.service';
import { User } from '../shared/models/user.model';

@Component({
  selector: 'app-page-name',
  templateUrl: './page-name.component.html',
  styleUrls: ['./page-name.component.css'],
})
export class PageNameComponent implements OnInit {
  public user$: Observable<User> = new Observable();
  public members$: Observable<any> = new Observable();

  constructor(
    private authService: MsalService,
    private msalService: MsalUserService,
    private membersService: MembersService
  ) {}

  public ngOnInit(): void {
    console.log('loginn');
    this.user$ = this.msalService.getProfile();

    // Uncomment this for just getting a token to access the api.
    // const requestObj = {
    //   scopes: ['api://1e0feb1f-cf22-4265-9ad7-995a009f39ea/clubmanagement'],
    // };

    // this.authService
    //   .acquireTokenPopup(requestObj)
    //   .subscribe((tokenResponse) => {
    //     // Callback code here
    //     console.log(tokenResponse.accessToken);
    //   });

    this.members$ = this.membersService.getMembers();
  }
}

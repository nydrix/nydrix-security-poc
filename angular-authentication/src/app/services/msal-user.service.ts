import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';

import { User } from '../shared/models/user.model';

@Injectable({
  providedIn: 'root',
})
export class MsalUserService {
  private readonly graphMeEndpoint: string =
    'https://graph.microsoft.com/v1.0/me';

  constructor(private http: HttpClient) {}

  public getProfile(): Observable<User> {
    return this.http.get<User>(this.graphMeEndpoint);
  }
}

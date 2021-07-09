import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root',
})
export class MembersService {
  constructor(private http: HttpClient) {}

  getMembers(): Observable<any> {
    return this.http.get<any>('http://localhost:8181/api/v1/members');
  }
}

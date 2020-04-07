import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { global } from '../global';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class VotacionesBryanmService {
  public url: string;
  token = localStorage.getItem('token').replace(/['"]+/g, '');

  constructor(public _http: HttpClient) {
    this.url = global.url;
  }
}

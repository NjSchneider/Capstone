import { Injectable } from '@angular/core';
import { Observable, Subject } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class SearchService {

  private search = new Subject<string>();

  constructor() { }

  // recieves and stores the entered searchItem
  sendSearch(searchItem: string){
    this.search.next(searchItem);
  }

  // sends the stored searchItem
  getSearch(): Observable<string>{
    return this.search.asObservable();
  }

}
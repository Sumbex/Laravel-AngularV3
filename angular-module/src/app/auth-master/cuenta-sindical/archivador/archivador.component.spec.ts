import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ArchivadorComponent } from './archivador.component';

describe('ArchivadorComponent', () => {
  let component: ArchivadorComponent;
  let fixture: ComponentFixture<ArchivadorComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ArchivadorComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ArchivadorComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

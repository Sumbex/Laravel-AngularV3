import { TestBed } from '@angular/core/testing';

import { VotacionesBryanmService } from './votaciones-bryanm.service';

describe('VotacionesBryanmService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: VotacionesBryanmService = TestBed.get(VotacionesBryanmService);
    expect(service).toBeTruthy();
  });
});

var parents = tree.getAllItemsWithKids();
                    var level = 0;
                    for(var i = 0 ; i < parents.length; i++)
                    {
                        level = tree.getLevel(parents[i]);
                        switch(level)
                        {
                            case 1:
                                break;
                            case 2:
                                tree.openItem(parents[i]);
                                break;
                            default:
                                tree.closeItem(parents[i]);
                                break;
                        }
                    }